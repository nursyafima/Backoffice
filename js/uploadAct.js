const images = ["jpg", "gif", "png", "jpeg"];
const videos = ["mp4", "3gp", "ogg", "mkv", "m4v", "avi", "wmv"];
var urlArrayImg = [];
var urlArrayVid = [];
//Listen for file selection
document.getElementById("files").addEventListener("change", function (e) {
  //Get files
  for (var i = 0; i < e.target.files.length; i++) {
    var imageFile = e.target.files[i];
    var filename = imageFile.name;
    var fileExt = fileExtension(filename);
    if (images.includes(fileExt)) {
      uploadImageAsPromise(imageFile);
    } else {
      uploadVideoAsPromise(imageFile);
    }
  }
});

//Handle waiting to upload each file using promise
function uploadImageAsPromise(imageFile) {
  return new Promise(function (resolve, reject) {
    var storageRef = firebase.storage().ref("daycare/activity/" + imageFile.name);

    //Upload file
    var task = storageRef.put(imageFile);

    //Update progress bar
    task.on(
      "state_changed",
      function progress(snapshot) {
        var percentage =
          (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        document.getElementById("progress_bar").value = percentage;
      },
      function error(err) {},
      function complete() {
        task.snapshot.ref.getDownloadURL().then(function (downloadURL) {
          urlArrayImg.push(downloadURL);
          console.log(downloadURL);
          const imageUrl = document.getElementById("imageUrl");
          imageUrl.setAttribute("value", urlArrayImg.toString().split(","));
          console.log(imageUrl);
        });
      }
    );
  });
}

function uploadVideoAsPromise(imageFile) {
  return new Promise(function (resolve, reject) {
    var storageRef = firebase.storage().ref("daycare/activity/" + imageFile.name);

    //Upload file
    var task = storageRef.put(imageFile);

    //Update progress bar
    task.on(
      "state_changed",
      function progress(snapshot) {
        var percentage =
          (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        document.getElementById("progress_bar").value = percentage;
      },
      function error(err) {},
      function complete() {
        task.snapshot.ref.getDownloadURL().then(function (downloadURL) {
          urlArrayVid.push(downloadURL);
          console.log(downloadURL);
          const videoUrl = document.getElementById("videoUrl");
          videoUrl.setAttribute("value", urlArrayVid.toString().split(","));
          console.log(videoUrl);
        });
      }
    );
  });
}

function fileExtension(filename) {
  var a = filename.toString().split(".").pop();

  return a;
}
