const state = () => ({
  guestCreateActionPopupStatus: false,
  takeParticipationPopupStatus: false,
  auth: {
    authorized: false,
    role: null,
    userData: {
      active: null,
      id: '',
      login: '',
      email: '',
      firstName: '',
      lastName: '',
      image: '',
      profile: {},
      notifications: [],
      unreadNotifications: 0,
    }
  },
  // notificationSound: new Audio(require("~/assets/audio/notification.mp3"))
})

export default state