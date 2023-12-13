import { getStorage, ref, getDownloadURL } from "firebase/storage";

var url = document.getElementById("urlInvoice").getAttribute("value");

const storage = getStorage();
getDownloadURL(ref(storage, "daycare/invoice/1000.pdf"))
  .then((url) => {
    // `url` is the download URL for 'images/stars.jpg'

    // This can be downloaded directly:
    console.log(url);
    const xhr = new XMLHttpRequest();
    xhr.responseType = "blob";
    xhr.onload = (event) => {
      const blob = xhr.response;
    };
    xhr.open("GET", url);
    xhr.send();

    // // Or inserted into an <img> element
    // const img = document.getElementById('myimg');
    // img.setAttribute('src', url);
  })
  .catch((error) => {
    // Handle any errors
  });
