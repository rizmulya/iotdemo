// #include <ESP8266WiFi.h> // for ESP8266
// https://github.com/256dpi/arduino-mqtt
#include <WiFi.h>
#include <MQTT.h>
#include <NusabotSimpleTimer.h>

WiFiClient net;
MQTTClient client;
NusabotSimpleTimer timer;

const String serialNumber = "12345678";

const char ssid[] = "Wokwi-GUEST";
const char pass[] = "";

// Variabel Pin GPIO
const int pinRed = 27;
const int pinGreen = 26;
const int pinBlue = 25;
const int pinLed = 33;
const int pinPot = 35;

int pot, oldPot = 0;
int targetPot = 0;
int brightness = 0;
String currentColor = "off";

void rgb(int red, int green, int blue) {
  analogWrite(pinRed, red);
  analogWrite(pinGreen, green);
  analogWrite(pinBlue, blue);
}

// ======= SETUP =======
void setup() {
  pinMode(pinRed, OUTPUT);
  pinMode(pinGreen, OUTPUT);
  pinMode(pinBlue, OUTPUT);
  pinMode(pinLed, OUTPUT);

  WiFi.begin(ssid, pass);
  client.begin("USERNAME.cloud.shiftr.io", net);

  client.onMessage(subscribe);
  timer.setInterval(1000, publish);

  connect();
}

// ======= LOOP =======
void loop() {
  client.loop(); // loop onMessage, subscribe&publish

  timer.run();

  if (!client.connected()) {
    connect();
  }

  delay(10); // this speeds up the simulation
}

void connect() {
  digitalWrite(pinLed, 1);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
  }
  digitalWrite(pinLed, 0);

  while (!client.connect("12345678", "USERNAME", "PASSWORD")) { //param: esp32_identifier,username,password
    delay(500);
  }
  digitalWrite(pinLed, 1);

  client.subscribe("USERNAME/#", 1); // qs1
}

void publish() {
  pot = analogRead(pinPot);
  brightness = map(pot, 0, 4095, 0, 255);

  if (pot != oldPot) {
    client.publish("USERNAME/" + serialNumber + "/potentiometer", String(pot), false, 1); // param: topic,data,rentain(back_to_last_state),qs
    oldPot = pot;
  }
}

void subscribe(String &topic, String &data) {
  if (topic == "USERNAME/" + serialNumber + "/led") {
    currentColor = data;
    if (brightness == 0) {
      brightness = map(targetPot, 0, 4095, 0, 255);
    }
    applyColor(currentColor, brightness);
  } else if (topic == "USERNAME/" + serialNumber + "/potentiometer") {
    targetPot = data.toInt();
    brightness = map(targetPot, 0, 4095, 0, 255);
    applyColor(currentColor, brightness);
  }
}

void applyColor(String color, int brightness) {
  if (color == "red") {
    rgb(brightness, 0, 0);
  } else if (color == "green") {
    rgb(0, brightness, 0);
  } else if (color == "blue") {
    rgb(0, 0, brightness);
  } else if (color == "white") {
    rgb(brightness, brightness, brightness);
  } else if (color == "off") {
    rgb(0, 0, 0);
  }
}
