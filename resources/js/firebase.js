import { initializeApp } from "firebase/app";
import {
    getMessaging,
    getToken,
    onMessage,
    isSupported
} from "firebase/messaging";

console.log("Firebase JS Loaded");

const firebaseConfig = {
    apiKey: "AIzaSyA5W6dr7PhFUaUFs_lVGUq_mlbmDufFAAU",
    authDomain: "mahera-jewels.firebaseapp.com",
    projectId: "mahera-jewels",
    storageBucket: "mahera-jewels.firebasestorage.app",
    messagingSenderId: "575116235950",
    appId: "1:575116235950:web:1414e16fd5858f5314c93f"
};

const app = initializeApp(firebaseConfig);

async function initFCM() {

    try {

        console.log("STEP 1");

        if (!("Notification" in window)) {
            console.log("Notifications not supported");
            return;
        }

        if (!("serviceWorker" in navigator)) {
            console.log("Service Worker not supported");
            return;
        }

        const supported = await isSupported();

        if (!supported) {
            console.log("Firebase Messaging not supported");
            return;
        }

        console.log("STEP 2");

        const registration = await navigator.serviceWorker.register(
            "/firebase-messaging-sw.js"
        );

        console.log("Service Worker Registered", registration);

        let permission = Notification.permission;

        if (permission === "default") {
            permission = await Notification.requestPermission();
        }

        console.log("Permission :", permission);

        if (permission !== "granted") {
            console.log("Notification permission denied");
            return;
        }

        const messaging = getMessaging(app);

        const token = await getToken(messaging, {
            vapidKey: "BA2PaCB4hQHPn73NIM92WfUipkq1h1pI6KgTOtk3yENW9CRJctmqf6GmIjUOq7qEjeXBE8Hxgr5_wYJz9bQeGko",
            serviceWorkerRegistration: registration
        });

        if (!token) {
            console.log("No Token Received");
            return;
        }

        console.log("FCM TOKEN");
        console.log(token);

        const oldToken = localStorage.getItem("fcm_token");

        if (oldToken !== token) {

            localStorage.setItem("fcm_token", token);

            const response = await fetch("/admin/notification/save-token", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                },
                body: JSON.stringify({
                    fcm_token: token,
                    browser: navigator.userAgent,
                    platform: navigator.platform
                })
            });

            console.log("Token Saved");
            console.log(await response.text());

        } else {

            console.log("Token Already Saved");

        }

        onMessage(messaging, (payload) => {

            console.log("Foreground Notification");
            console.log(payload);

            if (payload.notification) {

                new Notification(payload.notification.title, {
                    body: payload.notification.body,
                    icon: payload.notification.icon || "/favicon.ico"
                });

            }

        });

    } catch (error) {

        console.error("FCM Error");
        console.error(error);

    }

}

window.addEventListener("load", initFCM);