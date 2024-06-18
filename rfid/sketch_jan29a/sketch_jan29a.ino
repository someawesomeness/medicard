#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <Servo.h>
#include <ArduinoJson.h>

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//----------------------------------------Include the SPI and MFRC522 libraries-------------------------------------------------------------------------------------------------------------//
//----------------------------------------Download the MFRC522 / RC522 library here: https://github.com/miguelbalboa/rfid
#include <SPI.h>
#include <MFRC522.h>
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

#define SS_PIN D2  //--> SDA / SS is connected to pinout D2
#define RST_PIN D1  //--> RST is connected to pinout D1
MFRC522 mfrc522(SS_PIN, RST_PIN);  //--> Create MFRC522 instance.
WiFiClient wifiClient;

Servo myServo;  // create servo object to control a servo
Servo myServo2;  // create servo object to control a servo
Servo myServo3;  // create servo object to control a servo
Servo myServo4;  // create servo object to control a servo
Servo myServo5;  // create servo object to control a servo
const int servoPin = D3; // change this to the pin you connected the servo's signal wire
const int servoPin2 = D4; // change this to the pin you connected the servo's signal wire
const int servoPin3 = D8; // change this to the pin you connected the servo's signal wire
const int servoPin4 = D0; // change this to the pin you connected the servo's signal wire
const int servoPin5 = D0; // change this to the pin you connected the servo's signal wire

#define ON_Board_LED 2  //--> Defining an On Board LED, used for indicators when the process of connecting to a wifi router

//----------------------------------------SSID and Password of your WiFi router-------------------------------------------------------------------------------------------------------------//
const char* ssid = "Huawei Y7 Pro 2019";
const char* password = "12345678";
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

ESP8266WebServer server(80);  //--> Server on port 80

int readsuccess;
byte readcard[4];
char str[32] = "";
String StrUID;
String payloadGet;
String pid;
String payloadGetP;

// List of authorized UIDs
String authorizedUIDs[10] = {payloadGet}; // Replace with actual UIDs
int numberOfAuthorizedUIDs = 10;

// Make sure you have declared the product_names array with sufficient size
// Define an array of authorized product IDs
String authorizedPIDs[9] = {payloadGetP}; // Add your authorized PIDs here
int numberOfAuthorizedPIDs = 9; // Update this number based on your actual number of authorized PIDs

//-----------------------------------------------------------------------------------------------SETUP--------------------------------------------------------------------------------------//
void setup() {
  Serial.begin(115200); // Start serial communication at a baud rate of 115200
  // ... rest of your setup code ...

  // In your loop or a function
  Serial.println("Message from NodeMCU"); // Send a message to Arduino

  SPI.begin();      //--> Init SPI bus
  mfrc522.PCD_Init(); //--> Init MFRC522 card
  myServo.attach(servoPin);
  myServo2.attach(servoPin2);
  myServo3.attach(servoPin3);
  myServo4.attach(servoPin4);
  myServo5.attach(servoPin5);

  delay(500);

  WiFi.begin(ssid, password); //--> Connect to your WiFi router
  Serial.println("");
    
  pinMode(ON_Board_LED,OUTPUT); 
  digitalWrite(ON_Board_LED, HIGH); //--> Turn off Led On Board

  //----------------------------------------Wait for connection
  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    //----------------------------------------Make the On Board Flashing LED on the process of connecting to the wifi router.
    digitalWrite(ON_Board_LED, LOW);
    delay(250);
    digitalWrite(ON_Board_LED, HIGH);
    delay(250);
  }
  digitalWrite(ON_Board_LED, HIGH); //--> Turn off the On Board LED when it is connected to the wifi router.
  //----------------------------------------If successfully connected to the wifi router, the IP Address that will be visited is displayed in the serial monitor
  Serial.println("");
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  server.on("/", []() {
    server.send(200, "text/plain", "Hello from NodeMCU!");
  });
  server.begin(); // Start the server
  Serial.println(WiFi.localIP());

  Serial.println("Please tag a card or keychain to see the UID !");
  Serial.println("");

  // Your setup code here, which includes getting payloadGet from the server
  HTTPClient http;

      String UIData, getData;
      String id = "";
      getData = "id=" + String(id);
      Serial.println("Request Link:"); 
      http.begin(wifiClient, "http://192.168.43.240/mediclean/rfid/getUIData.php");
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      int httpCodeGet = http.POST(getData);
      String payloadGet = http.getString();
      Serial.print("Response Code: ");
      Serial.println(httpCodeGet);   //Print HTTP return code
      Serial.print("Response Payload: ");
      Serial.println(payloadGet);    //Print request response payload
  
  // Now you can assign payloadGet to an element of the array
  payloadGet.trim(); // This will modify payloadGet by removing any whitespace or newline characters
  authorizedUIDs[0] = payloadGet; // Now you can assign the trimmed payloadGet to the first element of the array

  // Parse JSON array
  DynamicJsonDocument doc(1024);
  deserializeJson(doc, payloadGet);
  JsonArray arr = doc.as<JsonArray>();

  // Store each UID in authorizedUIDs
  for (int i = 0; i < arr.size(); i++) {
    authorizedUIDs[i] = arr[i].as<String>();
}
      String Prodata, getPdata;
      String pid = ""; // Set this to the actual product ID you want to send
      getPdata = "id=" + pid; // This will create the POST data with the product ID
      http.begin(wifiClient, "http://192.168.43.240/mediclean/rfid/product-get.php");
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      int httpCodeGetP = http.POST(getPdata);
      String payloadGetP = http.getString();
      Serial.print("Response Code: ");
      Serial.println(httpCodeGetP);   //Print HTTP return code
      Serial.print("Response Payload: ");
      Serial.println(payloadGetP);    //Print request response payload
  
  // Now you can assign payloadGet to an element of the array
  payloadGetP.trim(); // This will modify payloadGet by removing any whitespace or newline characters
  authorizedPIDs[0] = payloadGetP; // Now you can assign the trimmed payloadGet to the first element of the array

  // Parse JSON array
  deserializeJson(doc, payloadGetP);
  arr = doc.as<JsonArray>();

  // Store each UID in authorizedUIDs
for (int i = 0; i < arr.size(); i++) {
  authorizedPIDs[i] = arr[i]["pid"].as<String>(); // Assuming each object in the JSON array has a "UID" field
}
 
http.end();
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//-----------------------------------------------------------------------------------------------LOOP---------------------------------------------------------------------------------------//
void loop() {
  server.handleClient(); // 
  // put your main code here, to run repeatedly
  readsuccess = getid();
 
  if(readsuccess) {  
  digitalWrite(ON_Board_LED, LOW);
    HTTPClient http;    //Declare object of class HTTPClient
 
    String UIDresultSend, postData;
    UIDresultSend = StrUID;
   
    //Post Data
    postData = "UIDresult=" + UIDresultSend; 
  
    http.begin(wifiClient, "http://192.168.43.240/mediclean/rfid/getUID.php");  //Specify request destination
    http.addHeader("Content-Type", "application/x-www-form-urlencoded"); //Specify content-type header
   
    int httpCode = http.POST(postData);   //Send the request
    String payload = http.getString();    //Get the response payload
  
    Serial.println(UIDresultSend);
    Serial.println(httpCode);   //Print HTTP return code
    Serial.println(payload);    //Print request response payload

     bool isAuthorized = false;
      for (int i = 0; i < numberOfAuthorizedUIDs; i++) {
        // Debugging code
        Serial.print("Comparing: ");
        Serial.print(StrUID);
        Serial.print(" with: ");
        Serial.println(authorizedUIDs[i]);
          if (StrUID == authorizedUIDs[i]) {
              isAuthorized = true;
              break;
          }
      }
      bool isPIDAuthorized = false;
        for (int i = 0; i < numberOfAuthorizedPIDs; i++) {
          // Debugging code
        Serial.print("Comparing: ");
        Serial.print(pid);
        Serial.print(" with: ");
        Serial.println(authorizedPIDs[i]);
          if (pid == authorizedPIDs[i]) {
              isPIDAuthorized = true;
              break;
          }
        }


    if(isAuthorized) {
      Serial.println("Access Granted");
      // Spin continuously at full speed in one direction for 2 seconds
      myServo.write(0);
      myServo2.write(0);
      myServo3.write(0);
      myServo4.write(0);
      myServo5.write(0);
      delay(10000);
      // Stop spinning
      myServo.write(90);
      myServo2.write(90);
      myServo3.write(90);
      myServo4.write(90);
      myServo5.write(90);
      delay(1000);
    } else {
      Serial.println("Access Denied");
      // Keep servo stationary or perform another action
    }

  // You can repeat this sequence or create your own pattern

    http.end();  //Close connection
    delay(1000);
  digitalWrite(ON_Board_LED, HIGH);
    
  }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//----------------------------------------Procedure for reading and obtaining a UID from a card or keychain---------------------------------------------------------------------------------//
int getid() {  
  if(!mfrc522.PICC_IsNewCardPresent()) {
    return 0;
  }
  if(!mfrc522.PICC_ReadCardSerial()) {
    return 0;
  }
 
  
  Serial.print("THE UID OF THE SCANNED CARD IS : ");
  
  for(int i=0;i<4;i++){
    readcard[i]=mfrc522.uid.uidByte[i]; //storing the UID of the tag in readcard
    array_to_string(readcard, 4, str);
    StrUID = str;
  }
  mfrc522.PICC_HaltA();
  return 1;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//----------------------------------------Procedure to change the result of reading an array UID into a string------------------------------------------------------------------------------//
void array_to_string(byte array[], unsigned int len, char buffer[]) {
    for (unsigned int i = 0; i < len; i++)
    {
        byte nib1 = (array[i] >> 4) & 0x0F;
        byte nib2 = (array[i] >> 0) & 0x0F;
        buffer[i*2+0] = nib1  < 0xA ? '0' + nib1  : 'A' + nib1  - 0xA;
        buffer[i*2+1] = nib2  < 0xA ? '0' + nib2  : 'A' + nib2  - 0xA;
    }
    buffer[len*2] = '\0';
}
