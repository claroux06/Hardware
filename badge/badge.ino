#include "SPI.h"
#include "MFRC522.h"
#include <HTTPClient.h>
#include <WiFi.h>
#include "time.h"

const char* ntpServer = "pool.ntp.org";
const long  gmtOffset_sec = 3600;
const int   daylightOffset_sec = 3600;

const char* ssid = "testse32";
const char* password = "kkyp9695";

String HOST_NAME = "http://192.168.156.53"; // Entrez l'adresse IP de l'appareil.
String PATH_NAME = "/users.php";
String queryString = "?uid=";


#define RST_PIN 2 // Broche RES
#define SS_PIN 5 // Broche SDA (ou SS)


byte readCard[4];
String tagID = ""; // Tableau contenant les ID refusés.
MFRC522 mfrc522(SS_PIN, RST_PIN);


void printLocalTime()
{
  struct tm timeinfo;
  if(!getLocalTime(&timeinfo)){
    Serial.println("Failed to obtain time");
    return;
  }
  Serial.println(&timeinfo, "%A, %B %d %Y %H:%M:%S");
}


void setup() {
  Serial.begin(115200);
  
  SPI.begin();
  mfrc522.PCD_Init();
  
  pinMode(1, OUTPUT);
  pinMode(6, OUTPUT);
  pinMode(3, OUTPUT);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  Serial.println("Connecting");

  while (WiFi.status() != WL_CONNECTED) {
    delay(600);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
  printLocalTime();

}

// Fonction pour gérer l'allumage des LEDs en fonction de la réponse du serveur
void LED(int response) {
  if (response == 1) {
    Serial.println("Accès autorisé.");
    digitalWrite(1, HIGH);
    delay(2000);
    digitalWrite(1, LOW);
  }
  else if (response == 0) {
    Serial.println("Accès refusé.");
    digitalWrite(6, HIGH);
    delay(2000);
    digitalWrite(6, LOW);
  }
  else if (response == 2) {
    Serial.println("Utilisateur non identifié.");
    digitalWrite(3, HIGH);
    delay(2000);
    digitalWrite(3, LOW);
  }
}

// Fonction pour obtenir l'identifiant de la carte RFID
boolean getID() {
  if (!mfrc522.PICC_IsNewCardPresent()) {
    return false;
  }
  if (!mfrc522.PICC_ReadCardSerial()) {
    return false;
  }
  tagID = "";
  for (uint8_t i = 0; i < 4; i++) {
    tagID.concat(String(mfrc522.uid.uidByte[i], HEX));
  }
  tagID.toUpperCase();
  mfrc522.PICC_HaltA();
  return true;
}

void loop() {
  HTTPClient http;

  while (getID()) {
    queryString = "?uid=" + tagID;  // Reset queryString and append the new tagID

    Serial.print("Tag ID: ");
    Serial.println(tagID);

    http.begin(HOST_NAME + PATH_NAME + queryString);
    int httpCode = http.GET();
    Serial.println("HTTP Code: " + String(httpCode));
    Serial.println("URL: " + HOST_NAME + PATH_NAME + queryString);

    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println("Server Response: " + payload);
    // Convertir la réponse en un entier
    int responseInt = payload.toInt();
    // Appeler la fonction LED avec la réponse convertie
    LED(responseInt);
    }
    else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }
}


