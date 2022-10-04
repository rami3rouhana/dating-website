import { dating_pages } from './index.js';

export const message = async (userID) => {
    window.location.href = "http://127.0.0.1:5500/message.html?id=" + userID;
}

export const block = async (userID, page) => {
    const blockedData = await dating_pages.getAPI(`/toggleBlock/${userID}`);
    if (blockedData.status === "success")
        document.getElementById(userID).remove();
}

export const favorite = async (userID, page) => {
    debugger
    const favoritedData = await dating_pages.getAPI(`/toggleFavorites/${userID}`);
    if (favoritedData.status === "success")
        document.getElementById(userID).remove();
}