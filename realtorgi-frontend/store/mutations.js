export const ADD_PROFILE = (state, {profileData}) => {
  state.auth.userData.profiles.push(profileData)
}

export const CHANGE_AUTH_STATUS = (state, {authStatus, userData}) => {
  state.auth.authorized = authStatus
  state.auth.userData = userData
}

export const CHANGE_GUEST_CREATE_ACTION_POPUP_STATUS = (state) => {
  state.guestCreateActionPopupStatus = !state.guestCreateActionPopupStatus
}

export const CHANGE_TAKE_PARTICIPATION_POPUP_STATUS = (state) => {
  state.takeParticipationPopupStatus = !state.takeParticipationPopupStatus
}

export const ADD_NOTIFICATION = (state, {notification}) => {
  state.auth.userData.notifications.pop(notification)
}

export const GET_NOTIFICATIONS = (state, {notifications}) => {
  state.auth.userData.notifications = notifications
  state.auth.userData.notifications.sort(function(a, b) {
    return parseFloat(Date.parse(b.created_at)) - parseFloat(Date.parse(a.created_at));
  });
}

export const CHANGE_EMAIL_ACTIVE_STATUS = (state, {activeStatus}) => {
  state.auth.userData.active = activeStatus
}

export const COUNT_UNREAD_NOTIFICATIONS = (state ) => {
  let unreadNotifications = state.auth.userData.notifications.filter(item => (item.status === 'new'))
  state.auth.userData.unreadNotifications = unreadNotifications.length
}