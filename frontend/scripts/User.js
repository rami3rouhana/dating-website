export const User = (userInfo, page) => {
  debugger
  return `
        <div class="card" id="${userInfo.users_id || userInfo.id}">
        <img src="http://127.0.0.1:8000/api/v0.1/image/${userInfo.image}" alt="Avatar" >
        <div class="container">
          <h4><b>Name: ${userInfo.name}</b></h4>
          <p>Gender: ${userInfo.gender === 1 ? "Male" : userInfo.gender === 2 ? "Female" : "Non Binary"}</p>
          <p>Age: ${userInfo.age}</p>
          <p>Bio: ${userInfo.bio}</p>
        </div>
        ${page === "favorite" ? `
        <button class="button change" role="button" onclick="module.message(${userInfo.id}, '${page}')">Message</button>
        <button class="button block" role="button" onclick="module.block(${userInfo.id}, '${page}')">Block</button>
        `: `
        <button class="button change" role="button" onclick="module.favorite(${userInfo.id}, '${page}')">Favorite</button>
        <button class="button block" role="button" onclick="module.block(${userInfo.id}, '${page}')">Block</button>
        `}

      </div>
  `
}