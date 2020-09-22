const examplePage = 'https://dashboard.myauris.vn/call/call';
const urlPage = 'https://dashboard.myauris.vn';

function openWindow(event) {
    /**** START notificationOpenWindow ****/
    /* const examplePage = 'https://dashboard.myauris.vn/call/call'; */
    const promiseChain = clients.openWindow(urlPage);
    event.waitUntil(promiseChain);
    /**** END notificationOpenWindow ****/
}

function focusWindow(event) {
    console.log('service worker function focus window', self.location);
    /**** START notificationFocusWindow ****/
    /**** START urlToOpen ****/
    // const urlToOpen = new URL(examplePage, self.location.origin).href;
    /**** END urlToOpen ****/

    /**** START clientsMatchAll ****/
    const promiseChain = clients.matchAll({
        type: 'window',
        includeUncontrolled: true
    })
    /**** END clientsMatchAll ****/
    /**** START searchClients ****/
    .then((windowClients) => {
        let matchingClient = null;

        for (let i = 0; i < windowClients.length; i++) {
            const windowClient = windowClients[i];
            /*console.log('windowClient', windowClients[i]);*/
            /* Mở trang đầu tiên tìm được - tìm theo urlPage (bắt đầu bằng https://dashboard.myauris.vn) */
            if (windowClient.url.indexOf(urlPage) != -1) {
                matchingClient = windowClient;
                break;
            }
        }

        if (matchingClient) {
            return matchingClient.focus();
        } else {
            return clients.openWindow(urlToOpen);
        }
    });
    /**** END searchClients ****/

    event.waitUntil(promiseChain);
    /**** END notificationFocusWindow ****/
}

/**** START notificationActionClickEvent ****/
self.addEventListener('notificationclick', function(event){
    console.log('service worker notificationclick', self.location);
    console.log('service worker event', event);
    if (!event.action) {
        // Was a normal notification click
        console.log('Notification Click.');
        return;
    }
    var title = event.notification.title,
        arr = title.split('-'),
        phone = arr[1].trim(),
        urlToOpen = '',
        promiseChain,
        windowClient;
    switch (event.action) {
        case 'answer':
            /**** START notificationFocusWindow ****/
            /**** START urlToOpen ****/
            /*urlToOpen = new URL(examplePage, self.location.origin).href;*/
            /**** END urlToOpen ****/

            /**** START clientsMatchAll ****/
            promiseChain = clients.matchAll({
                type: 'window',
                includeUncontrolled: true
            })
            /**** END clientsMatchAll ****/
            /**** START searchClients ****/
            .then((windowClients) => {
                let matchingClient = null;

                for (let i = 0; i < windowClients.length; i++) {
                    windowClient = windowClients[i];
                    if (windowClient.url.indexOf(urlPage) != -1) {
                        matchingClient = windowClient;
                        break;
                    }
                }

                if (matchingClient) {
                    matchingClient.postMessage({
                        phone: phone,
                        action: 'answer-call'
                    });
                    console.log(matchingClient);
                } else {
                    console.log('not found');
                }
            });
            /**** END searchClients ****/

            event.waitUntil(promiseChain);
            /**** END notificationFocusWindow ****/
            break;
        case 'reject':
            /**** START notificationFocusWindow ****/
            /**** START urlToOpen ****/
            /*urlToOpen = new URL(examplePage, self.location.origin).href;*/
            /**** END urlToOpen ****/

            /**** START clientsMatchAll ****/
            promiseChain = clients.matchAll({
                type: 'window',
                includeUncontrolled: true
            })
            /**** END clientsMatchAll ****/
            /**** START searchClients ****/
            .then((windowClients) => {
                let matchingClient = null;

                for (let i = 0; i < windowClients.length; i++) {
                    windowClient = windowClients[i];
                    if (windowClient.url.indexOf(urlPage) != -1) {
                        matchingClient = windowClient;
                        break;
                    }
                }

                if (matchingClient) {
                    matchingClient.postMessage({
                        phone: phone,
                        action: 'reject-call'
                    });
                    console.log(matchingClient);
                } else {
                    console.log('not found');
                }
            });
            /**** END searchClients ****/

            event.waitUntil(promiseChain);
            /**** END notificationFocusWindow ****/
            break;
        default:
            console.log(`Unknown action clicked: '${event.action}'`);
            break;
    }
});
/**** END notificationActionClickEvent ****/

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    switch(event.notification.tag) {
        case 'open-window':
            openWindow(event);
            break;
        case 'focus-window':
            focusWindow(event);
            break;
        default:
            // NOOP
            break;
    }
});

self.addEventListener('message', event => {
    console.log(event.data);
    console.log('service worker function focus window', self.location);
    /**** START notificationFocusWindow ****/
    /**** START urlToOpen ****/
    // const urlToOpen = new URL(examplePage, self.location.origin).href;
    /**** END urlToOpen ****/

    /**** START clientsMatchAll ****/
    const promiseChain = clients.matchAll({
        type: 'window',
        includeUncontrolled: true
    })
    /**** END clientsMatchAll ****/
    /**** START searchClients ****/
    .then((windowClients) => {
        let matchingClient = null;

        for (let i = 0; i < windowClients.length; i++) {
            const windowClient = windowClients[i];
            /*console.log('windowClient', windowClients[i]);*/
            /* Mở trang đầu tiên tìm được - tìm theo urlPage (bắt đầu bằng https://dashboard.myauris.vn) */
            if (windowClient.url.indexOf(urlPage) != -1) {
                windowClient.postMessage({
                    action: 'stop-phone-ring'
                });
            }
        }
    });
    /**** END searchClients ****/

    event.waitUntil(promiseChain);
    /**** END notificationFocusWindow ****/
});
