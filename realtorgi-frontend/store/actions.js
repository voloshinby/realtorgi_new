export const addProfile = ({ commit }, {profileData}) => {
  commit('ADD_PROFILE', { profileData })
}

export const changeAuthStatus = ({ commit }, { authStatus, userData }) => {
  commit('CHANGE_AUTH_STATUS', { authStatus, userData })
}

export const toggleGuestCreateActionPopup = ({ commit }) => {
  commit('CHANGE_GUEST_CREATE_ACTION_POPUP_STATUS',)
}

export const toggleTakeParticipationPopup = ({ commit }) => {
  commit('CHANGE_TAKE_PARTICIPATION_POPUP_STATUS',)
}

export const addNotification = ({ commit }, {notification}) => {
  commit('ADD_NOTIFICATION', { notification })
}

export const getNotifications = ({ commit }, {notifications}) => {
  commit('GET_NOTIFICATIONS', { notifications })
}

export const changeEmailActiveStatus = ({ commit }, {activeStatus}) => {
  commit('CHANGE_EMAIL_ACTIVE_STATUS', { activeStatus })
}

export const countUnreadNotifications = ({commit}) => {
  commit('COUNT_UNREAD_NOTIFICATIONS',)
}
