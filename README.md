# iotdemo

A fully integrated IoT system (Smart Lamp) featuring ESP32 and a real-time admin dashboard. For educational purposes only; improvements are needed for production deployment:
- use server-side connections to MQTT intead of client-side
- only show related data based on user privileges, this requires changes/additions to some tables and web code.

### How to Use

1. Setup microcontroler `ESP32/sketch.ino`.
2. Setup web, db const at `index.php` and run with `php -S localhost:8000`, and db const at `migrate` and run with `php migrate`. Finally, extract `public.rar`. login with the same username & password: 'admin' or 'user'.