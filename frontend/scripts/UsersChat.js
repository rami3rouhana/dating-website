export const selfChat = (userInfo, message = null) => {
  debugger
  return `
    <article class="msg-container msg-self" id="msg-0">
    <div class="msg-box">
      <div class="flr">
        <div class="messages">
          <p class="msg" id="msg-1">
          ${userInfo.message||message}
          </p>
        </div>
        <span class="timestamp"><span class="username">${userInfo.name}</span>&bull;<span class="posttime">${userInfo.created_at}</span></span>
      </div>
      <img class="user-img" id="user-0" src="http://127.0.0.1:8000/api/v0.1/image/${userInfo.image}" />
    </div>
  </article>
    `
}

export const othersChat = (userInfo) => {
  return `
    <article class="msg-container msg-remote" id="msg-0">
    <div class="msg-box">
      <img class="user-img" id="user-0" src="http://127.0.0.1:8000/api/v0.1/image/${userInfo.image}" />
      <div class="flr">
        <div class="messages">
          <p class="msg" id="msg-0">
          ${userInfo.message}
          </p>
        </div>
        <span class="timestamp"><span class="username">${userInfo.name}</span>&bull;<span class="posttime">${userInfo.created_at}</span></span>
      </div>
    </div>
  </article>
    `
}