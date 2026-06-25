importScripts("https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js");

firebase.initializeApp({
    apiKey: "AIzaSyA5W6dr7PhFUaUFs_lVGUq_mlbmDufFAAU",
    authDomain: "mahera-jewels.firebaseapp.com",
    projectId: "mahera-jewels",
    storageBucket: "mahera-jewels.firebasestorage.app",
    messagingSenderId: "575116235950",
    appId: "1:575116235950:web:1414e16fd5858f5314c93f"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {

    self.registration.showNotification(
        payload.notification.title,
        {
            body: payload.notification.body,
            icon: "/favicon.ico"
        }
    );

});