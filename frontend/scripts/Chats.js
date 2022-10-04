import { dating_pages } from './index.js';
import { selfChat, othersChat } from './UsersChat.js'

export const Chats = () => {

    const userId = window.location.search.split('=')[1];

    window.onload = async () => {
        const messagesData = await dating_pages.getAPI(`/receiveMessages/${userId}`);
        let users = '';
        if (messagesData.messages.length > 0) {
            messagesData.messages.map(msg => {
                if (userId == msg.users_id) {
                    users += othersChat(msg)
                } else {
                    users += selfChat(msg)
                }
            });
            document.getElementById("chat-box").innerHTML = users;
        } else {
            alert("You have no Matches");
        }
    }

    document.getElementById('sendMessage').addEventListener("click", async () => {
        const message = document.getElementById("message").value;
        const data = {
            mates_id:userId,
            message
        }
        const sentData = await dating_pages.postAPI(`/sendMessage`, data);
        document.getElementById("chat-box").innerHTML += selfChat(sentData.user,message);
    })
}