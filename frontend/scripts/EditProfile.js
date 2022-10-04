import { dating_pages } from './index.js';

export const EditProfile = () => {
    document.getElementById("register-btn").addEventListener("click", async (e) => {
        e.preventDefault();
        const email = document.getElementById("email-register").value;
        const name = document.getElementById("name-register").value;
        const password = document.getElementById("password-register").value;
        const age = document.getElementById("age-register").value;
        const gender = document.getElementById("gender").value;
        const intersted_in = document.getElementById("instested-in").value;
        const bio = document.getElementById("bio-register").value;

        const readFile = (file) => {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();

                reader.onload = res => {
                    resolve(res.target.result);
                };
                reader.onerror = err => reject(err);

                reader.readAsDataURL(file);
            });
        }
        const image = document.getElementById("image-register");

        let encryptedImage = await readFile(image.files[0]);

        encryptedImage = encryptedImage.split(",")[1];

        const extension = image.files[0].name.split(".")[image.files[0].name.split(".").length - 1];

        const data = {
            name,
            email,
            password,
            encryptedImage,
            extension,
            age,
            gender,
            intersted_in,
            bio
        };


        const profileData = await dating_pages.postAPI('/editUser', data);

        if (profileData.status === "success") {
            debugger
        } else {
            alert("Something Went Wrong!")
        }
    })
}