import { dating_pages } from './index.js';
import { User } from './User.js';

export const Dates = () => {

    window.onload = async () => {
        const datesData = await dating_pages.getAPI('/getUsers');
        let users = '';
        if (datesData.matches.length > 0) {
            datesData.matches.map(date => {
                users += User(date, "date");
            });
            document.getElementById("dates-cards").innerHTML = users;
        } else {
            alert("You have no Matches");
        }
    }
}