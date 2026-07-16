#include <SPI.h>
#include <Ethernet.h>
#include <PubSubClient.h>

byte mac[] = { 0xA0, 0x75, 0x69, 0x99, 0xD, 0x3D };

IPAddress ip(192, 168, 100, 30);
IPAddress server(192, 168, 100, 5);

EthernetClient ethClient;
PubSubClient client(ethClient);

void sendMsg() {
  client.publish("raspbroker", "TEMPTERATURA: 29"); 
  delay(2000);       
  if (!client.connected()) {
    reconnect();
  }
}

void reconnect() { 
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");   
    if (client.connect("arduinoClient")) {
      Serial.println("connected");        
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");     
      delay(5000);
    }
  }
}

void setup()
{
  Serial.begin(57600);

  client.setServer(server, 1883);
  
  Ethernet.begin(mac, ip);
  delay(1500);
}

void loop()
{  
  client.loop();
  
  int l = 1;
  
  for (int i = 0; i < l; i++) {
    sendMsg();
    l++;
  }  
}
