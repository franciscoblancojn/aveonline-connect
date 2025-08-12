const AVCONNECT_onCopy = (text, message = null, options = {}) => {
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard
      .writeText(text)
      .then(() => {
        if (message) {
          window.Notification.requestPermission().then((permission) => {
            if (permission == "granted") {
              new window.Notification(message, options);
            }
          });
        }
      })
      .catch((err) => {
        window.Notification.requestPermission().then((permission) => {
          if (permission == "granted") {
            new window.Notification("Error al Copiar", options);
          }
        });
      });
  } else {
    let tempInput = document.createElement("textarea");
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    if (message) {
      window.Notification.requestPermission().then((permission) => {
        if (permission == "granted") {
          new window.Notification(message, options);
        }
      });
    }
  }
};
