# PHP-DB2JSON-MODEL
PHP için  veritabanı tablolarını , JSON modellere dönüştürüp üzerinde işlem yapabilme sınıfı...
İstenen tablonun JSON modeli çıkarılıp, dosyaya kaydedilir. Tekrar istendiğinde dosyayı verir.

# DB
Sınıf için öncelikle bir veritabanı bağlamalısınız.


# ÇAĞIRIM
Static sınıftır, çağırımı kolaydır.

Örnek;
models::get("user")

# ARAMA
Tablo sütün, parametresi verilebilir, verilmezse "id" üzerinde arama yapar. 
Sonuç bulunmazsa "false" döner.
Tek kayıt bulunmuşsa model olarak döner.
Birden çok kayıt array model olarak döner.

Örnek:
models::get("user")->find("test","username");

# KAYIT
Model üzerinde işlemler yapılıp, sadece "save" zinciri kurulması yeterlidir. 
Dönüş olarak "lastinsertid" döner.

ÖRnek :
$user : models::get("user");
$user->username = "test user";
$user->password = md5("010101");
$user->save();
