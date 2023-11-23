#include "SPI.h"
#include "MFRC522.h"
#include <WiFi.h>
#include <HTTPClient.h>


const char* ssid = "Bbox-84DA2313";
const char* password = "rHXcg6JS9s4cF33r7D";


String HOST_NAME = "192.168.1.12"; // Entrez l'adresse IP de l'appareil.
String PATH_NAME = "../actions/users.php";
String queryString = "?uid=";


#define RST_PIN 2 // Broche RES
#define SS_PIN 5 // Broche SDA (ou SS)


int a = 1; // Variable globale représentant le nombre de personnes autorisées.
int b = 1; // Variable globale représentant le nombre de personnes refusées.


byte readCard[4];
String tagID = ""; // Tableau contenant les ID refusés.
MFRC522 mfrc522(SS_PIN, RST_PIN);


void setup() {
  Serial.begin(9600);
  
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
}


// Fonction pour gérer l'allumage des LEDs en fonction de la réponse du serveur
void LED(String response) {
  if (response == "Access granted") {
    Serial.println("Accès autorisé.");
    digitalWrite(1, HIGH);
    delay(2000);
    digitalWrite(1, LOW);
  }
  else if (response == "Access denied") {
    Serial.println("Accès refusé.");
    digitalWrite(6, HIGH);
    delay(2000);
    digitalWrite(6, LOW);
  }
  else if (response == "User unidentified") {
    Serial.println("Utilisateur non identifié.");
    digitalWrite(3, HIGH);
    delay(2000);
    digitalWrite(3, LOW);
  }
  else {
    Serial.println("Réponse inattendue du serveur : " + response);
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
    queryString = queryString + tagID;

    http.begin(HOST_NAME + PATH_NAME + queryString);
    int httpCode = http.GET();
    Serial.println(HOST_NAME + PATH_NAME + queryString);

    Serial.print("HTTP code: ");
    Serial.println(httpCode);

    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      LED(payload);
    }
    else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }
  queryString = "?uid=";
}