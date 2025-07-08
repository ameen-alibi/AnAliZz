
(function () {
    const token = '{{ tracking_token }}';
    const endpoint = 'https://api.analizz.com/api/track';
    const sessionId = localStorage.getItem('analytics_session') || crypto.randomUUID();
    localStorage.setItem('analytics_session', sessionId);

    function send(eventType, extra = {}) {
        navigator.sendBeacon
            ? navigator.sendBeacon(endpoint, JSON.stringify({
                token, session_id: sessionId, event_type: eventType,
                url: location.href, ...extra
            }))
            : fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token, session_id: sessionId, event_type: eventType, url: location.href, ...extra })
            });
    }

    send('pageview');


    [25, 50, 75, 100].forEach(threshold => {
        let sent = false;
        window.addEventListener('scroll', () => {
            if (!sent && (window.scrollY + window.innerHeight) / document.body.scrollHeight * 100 >= threshold) {
                send('scroll', { scroll_depth: threshold });
                sent = true;
            }
        });
    });


    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(heartbeatInterval);
        } else {
            heartbeatInterval = setInterval(() => {
                send('heartbeat', { duration_ms: 15000 });
            }, 15000);
        }
    });
})();

