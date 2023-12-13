document.getElementById("file").addEventListener("change", (event) => {
    const file = event.target.files[0];
    const filename = file.name;
    const storageRef = firebase.storage().ref('daycare/invoice/' + filename);

    storageRef.put(file).on('state_changed', (snapshot) => {
        const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        const progressBar = document.getElementById("progress_bars");
        progressBar.value = progress;
        storageRef.getDownloadURL().then(function(url){
            // const image = document.getElementById("image").setAttribute('src', url);
            const invoiceUrl = document.getElementById('invoiceUrl');
            invoiceUrl.setAttribute('value', url);
            console.log(invoiceUrl);
        });
    });

});