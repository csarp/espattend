 /*This code shared as is and no responsibility accepted
 * github csarp
 */

#include <ESP8266WiFi.h>     //Include Esp library
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>        //include RFID library

#define SS_PIN D8 //RX slave select
#define BuzzerGreen D1
#define BlueLed D2
#define RST_PIN D3
//#define BlueLed D4

MFRC522 mfrc5221(SS_PIN, RST_PIN); // Create MFRC522 instance.

/* Set these to your desired credentials. */
const char *ssid = "YourSSID";
const char *password = "YourWiFiPassWD";

//Web/Server address to read/write from 

const char* serverName = "http://100.100.100.100/attend/post-esp-data.php";
const char* host = "100.100.100.100";
String apiKeyValue = "A-Random-Api-Key-Here";

String getData, Link;
String CardID="";

void setup() {
  delay(1000);
  Serial.begin(115200);
  SPI.begin();  // Init SPI bus
  mfrc5221.PCD_Init(); // Init MFRC522 card

  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting to ");
  Serial.print(ssid);
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.println("Connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP

  pinMode(BuzzerGreen,OUTPUT);
  pinMode(BlueLed,OUTPUT);
  
}


void loop() {
  if(WiFi.status() != WL_CONNECTED){
    WiFi.disconnect();
    WiFi.mode(WIFI_STA);
    Serial.print("Reconnecting to ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
    Serial.println("");
    Serial.println("Connected");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());  //IP address assigned to your ESP
  }

  
  //look for new card
   if ( ! mfrc5221.PICC_IsNewCardPresent()) {
    return;//got to start of loop if there is no card present
  }
 
 // Select one of the cards
 if ( ! mfrc5221.PICC_ReadCardSerial()) {
  return;//if read card serial(0) returns 1, the uid struct contains the ID of the read card.
 }

digitalWrite(BlueLed,HIGH);

 // Dump debug info about the card; PICC_HaltA() is automatically called
 mfrc5221.PICC_DumpToSerial(&(mfrc5221.uid));

 for (byte i = 0; i < mfrc5221.uid.size; i++) {
     CardID += mfrc5221.uid.uidByte[i];
}
  
  HTTPClient http;    //Declare object of class HTTPClient
  
  getData = CardID;
  delay(1000);
  
  String httpRequestData = "api_key=" + apiKeyValue + "&rfid=" + getData;
  http.begin("http://100.100.100.100/attend/post-esp-data.php");
   // Specify content-type header
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
   
  // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);
  // delay(10);
            
    if(httpResponseCode == 200){
    digitalWrite(BuzzerGreen,HIGH);
    Serial.println("blue on - BuzzerGreen on"); //OK
    delay(500);  //Post Data at every 2 seconds
  }
  
  //Serial.println(payload);    //Print request response payload
  Serial.println(CardID);     //Print Card ID //OK
  
  /*if(payload == "login"){
    digitalWrite(BuzzerGreen,HIGH);
    //digitalWrite(BuzzerGreen,HIGH);
    Serial.println("red on");
    delay(200);  //Post Data at every 2 seconds
  }
  else if(payload == "logout"){
    digitalWrite(BlueLed,HIGH);
    //digitalWrite(BuzzerGreen,HIGH);
    Serial.println("Blue on");
    delay(200);  //Post Data at every 5 seconds
  }
  else if(payload == "succesful" || payload == "Cardavailable"){
    digitalWrite(BlueLed,HIGH);
    digitalWrite(BuzzerGreen,HIGH);
    //digitalWrite(BuzzerGreen,HIGH);
    delay(200);  
  }
  else if(payload == "NotAllow"){
    for(int j=0; j<5; j++){
    digitalWrite(BlueLed,HIGH);
    digitalWrite(BuzzerGreen,HIGH);
    //digitalWrite(BuzzerGreen,HIGH);
    delay(200);
    digitalWrite(BlueLed,LOW);
    digitalWrite(BuzzerGreen,LOW);
    //digitalWrite(BuzzerGreen,LOW);
    }
  }
  */
  //delay(100);
  CardID = "";
  getData = "";
  Link = "";
  http.end();  //Close connection
  digitalWrite(BuzzerGreen,LOW); // led and buzzer off
  digitalWrite(BlueLed,LOW);
}
//=======================================================================
