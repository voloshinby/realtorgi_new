export default [
    { path: '/admin/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/admin/profile', component: require('./components/Profile.vue').default },
    { path: '/admin/developer', component: require('./components/Developer.vue').default },
    { path: '/admin/users', component: require('./components/Users.vue').default },
    // { path: '/products', component: require('./components/product/Products.vue').default },
    // { path: '/product/tag', component: require('./components/product/Tag.vue').default },
    { path: '/admin/categories', component: require('./components/auctions/Category.vue').default },
    { path: '/admin/auctions', component: require('./components/auctions/Auction.vue').default },
    { path: '/admin/auctionConfirms', component: require('./components/auctionConfirms/AuctionConfirm.vue').default },
    { path: '/admin/bets', component: require('./components/auctions/AuctionBets.vue').default },
    { path: '/admin/lots', component: require('./components/auctions/Lots.vue').default },
    { path: '/admin/lots/:id', component: require('./components/auctions/LotItems.vue').default },
    { path: '/admin/lot_moderation', component: require('./components/auctions/LotsModerate.vue').default },
    // { path: '/bargainings', component: require('./components/auctions/Bargaining.vue').default },
    { path: '/admin/countries', component: require('./components/Country.vue').default },
    // { path: '/front/auctions_front', component: require('./components/front/Auctions.vue').default },
    // { path: '/front/auctions_front/:id', component: require('./components/front/Auction_one.vue').default },
    { path: '/admin/notifications', component: require('./components/Notifications.vue').default },
    { path: '/admin/change_users', component: require('./components/UserConfirm.vue').default },
    { path: '/admin/feedback', component: require('./components/Feedback.vue').default },
    { path: '/admin/messages', component: require('./components/Contacts.vue').default },
    { path: '/admin/export_data', component: require('./components/ExportData.vue').default },
    { path: '/admin/*', component: require('./components/NotFound.vue').default }
];
