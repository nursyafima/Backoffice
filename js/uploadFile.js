document.getElementById("file").addEventListener("change", (event) => {
    const file = event.target.files[0];
    const filename = file.name;
    const storageRef = firebase.storage().ref();

    storageRef.child('daycare/' + 'profile/' + filename).put(file).on('state_changed', (snapshot) => {
        const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        console.log(progress);
        const progressBar = document.getElementById("progress_bars");
        progressBar.value = progress;
        storageRef.child('daycare/' + 'profile/' + filename).getDownloadURL().then(function(url){
            const image = document.getElementById("image").setAttribute('src', url);
            const imageUrl = document.getElementById('profilePic');
            imageUrl.setAttribute('value', url);
            console.log(imageUrl);
        });
    });

});