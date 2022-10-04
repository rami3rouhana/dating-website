import { Landing } from './Landing.js';
import { Favorites } from './Favorites.js';
import { Dates } from './Dates.js';
import { Chats } from './Chats.js';
import { EditProfile } from './EditProfile.js';
import { message, block, favorite } from './EventListener.js'

export const dating_pages = {};

dating_pages.baseURL = "http://127.0.0.1:8000/api/v0.1";

dating_pages.Console = (title, values, oneValue = true) => {
  console.log('---' + title + '---');
  if (oneValue) {
    console.log(values);
  } else {
    for (let i = 0; i < values.length; i++) {
      console.log(values[i]);
    }
  }
  console.log('--/' + title + '---');
}

dating_pages.getAPI = async (api_url) => {
  try {
    const data = await axios(dating_pages.baseURL + api_url, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
    localStorage.setItem("token", data.data.authorisation.token);
    return data.data;
  } catch (error) {
    dating_pages.Console("Error from GET API", error);
  }
}

dating_pages.postAPI = async (api_url, api_data) => {
  try {
    const data = await axios.post(
      dating_pages.baseURL + api_url,
      api_data,
      {
        headers: {
          'Authorization': "Bearer " + localStorage.getItem('token')
        }
      }
    );
    localStorage.setItem("token", data.data.authorisation.token)
    return data.data;
  } catch (error) {

    dating_pages.Console("Error from POST API", error);
  }
}

dating_pages.loadFor = (page) => {
  eval("dating_pages.load_" + page + "();");
}

dating_pages.load_landing = Landing;

dating_pages.load_favorites = Favorites;

dating_pages.load_dates = Dates;

dating_pages.load_chats = Chats;

dating_pages.message = message;

dating_pages.block = block;

dating_pages.favorite = favorite;

dating_pages.load_profile = EditProfile;