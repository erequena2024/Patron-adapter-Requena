<?php

namespace RefactoringGuru\Adapter\RealWorld;

/**
 * La interfaz de destino representa la interfaz que ya siguen las clases de tu aplicación.
 */
interface Notification
{
    public function send(string $title, string $message);
}

/**
 * Un ejemplo de una clase existente que sigue la interfaz de destino.
 *
 * En muchas aplicaciones reales, esta interfaz puede no estar claramente definida.
 * Si ese es tu caso, podrías extender el adaptador desde una clase existente en tu aplicación.
 * Si eso es complicado (por ejemplo, SlackNotification no parece una subclase de EmailNotification),
 * entonces extraer una interfaz sería tu primer paso.
 */
class EmailNotification implements Notification
{
    private $adminEmail;

    public function __construct(string $adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

    public function send(string $title, string $message): void
    {
        mail($this->adminEmail, $title, $message);
        echo "Correo enviado con título '$title' a '{$this->adminEmail}' que dice '\n$message\n'.";
    }
}

/**
 * El Adapter es una clase útil, pero incompatible con la interfaz de destino.
 * No puedes simplemente cambiar el código de la clase para que siga la interfaz de destino,
 * ya que el código podría ser proporcionado por una biblioteca de terceros.
 */
class SlackApi
{
    private $login;
    private $apiKey;

    public function __construct(string $login, string $apiKey)
    {
        $this->login = $login;
        $this->apiKey = $apiKey;
    }

    public function logIn(): void
    {
        // Envía una solicitud de autenticación al servicio web de Slack.
        echo "Conectado a la cuenta de Slack '{$this->login}'.\n";
    }

    public function sendMessage(string $chatId, string $message): void
    {
        // Envía una solicitud POST al servicio web de Slack.
        echo "Mensaje publicado en el chat '$chatId': '$message'.\n";
    }
}

/**
 * El Adaptador es una clase que conecta la interfaz de destino con la clase Adapter.
 * En este caso, permite a la aplicación enviar notificaciones usando la API de Slack.
 */
class SlackNotification implements Notification
{
    private $slack;
    private $chatId;

    public function __construct(SlackApi $slack, string $chatId)
    {
        $this->slack = $slack;
        $this->chatId = $chatId;
    }

    /**
     * Un Adaptador no solo puede adaptar interfaces, sino también convertir
     * datos de entrada al formato requerido por el Adapter.
     */
    public function send(string $title, string $message): void
    {
        $slackMessage = "#" . $title . "# " . strip_tags($message);
        $this->slack->logIn();
        $this->slack->sendMessage($this->chatId, $slackMessage);
    }
}

/**
 * El código del cliente puede trabajar con cualquier clase que siga la interfaz de destino.
 */
function clientCode(Notification $notification)
{
    // ...

    echo $notification->send("¡El sitio web no responde!",
        "<strong style='color:red;font-size: 50px;'>¡Alerta!</strong> " .
        "Nuestro sitio web no está respondiendo. ¡Llama a los administradores!");

    // ...
}

echo "El código del cliente está diseñado correctamente y funciona con notificaciones por correo:\r\n\r\n";
$notification = new EmailNotification("soporte@ryaservicios.com");
clientCode($notification);



echo "<div style='padding: 10px; margin: 10px; width:800px;'>"; 
echo "<h4 style='color: #4CAF50; font-style: italic;'>EL MISMO CÓDIGO DEL CLIENTE PUEDE TRABAJAR CON OTRAS CLASES A TRAVÉS DE UN ADAPTADOR:</h4>";
echo "</div>";

$slackApi = new SlackApi("example.com", "XXXXXXXX");
$notification = new SlackNotification($slackApi, "Example.com Developers");
clientCode($notification);

echo "<br><br>"; // Espacio adicional al final
