# PHP-DB2JSON-MODEL
PHP için  veritabanı tablolarını , JSON modellere dönüştürüp üzerinde işlem yapabilme sınıfı...
İstenen tablonun JSON modeli çıkarılıp, dosyaya kaydedilir. Tekrar istendiğinde dosyayı verir.

# DB
Sınıf için öncelikle bir veritabanı bağlamalısınız.
Dışarıdan parametre olarakta alabilir, ancak "construct"ta tanımlamak daha pratik olacaktır.

Örnek;


function __construct(){


	global $db;
  
  
	self::$db = $db;
  
  
}


# START
Static sınıftır, çağırımı kolaydır.

Örnek;

models::get("user")

# FILTER
Model çağırımında, dönüş olarak , tüm modelin değilde istenen verilerin dönemsini sağlar.
Filter parametresi array verilmelidir.

Önek :

$user : models::get("user")->filter(["id","username"]);

# FIND
Tablo sütün, parametresi verilebilir, verilmezse "id" üzerinde arama yapar. 
Sonuç bulunmazsa "false" döner.
Tek kayıt bulunmuşsa model olarak döner.
Birden çok kayıt array model olarak döner.

Örnek:

models::get("user")->find("test","username");

# SAVE
Model üzerinde işlemler yapılıp, sadece "save" zinciri kurulması yeterlidir. 
Dönüş olarak "lastinsertid" döner.

Önek :

$user : models::get("user");

$user->username = "test user";

$user->password = md5("010101");

$user->save();

# UPDATE
Model üzerinde işlemler yapılıp, sadece "update" zinciri kurulması yeterlidir. 
Dönüş olarak başarılı işlem "true", başarısız işlem Hata mesajı döner.

Önek :

$user : models::get("user")->find("testuser");

$user->password = md5("010101");

$user->update();

# DELETE
Model üzerinde işlemler yapılıp, sadece "delete" zinciri kurulması yeterlidir. 
Dönüş olarak başarılı işlem "true", başarısız işlem Hata mesajı döner.

Önek :

$user : models::get("user")->delete(1,"id");




