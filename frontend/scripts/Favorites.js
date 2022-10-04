import { dating_pages } from './index.js';
import { User } from './User.js';

export const Favorites = () => {

    window.onload = async () => {
        const favoritesData = await dating_pages.getAPI('/getFavorites');
        let users = '';
        if (favoritesData.favorite.length > 0) {
            favoritesData.favorite.map(fav => {
                if (fav.length > 0)
                    users += User(fav[0], "favorite");
            });
            document.getElementById("favorite-cards").innerHTML = users;
        } else {
            alert("You have no Matches");
        }
    }
}