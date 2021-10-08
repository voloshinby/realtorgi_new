<template>
    <div>
        {{ auctions }}
    </div>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
      components: {
          VueTagsInput,
        },
        data () {
            return {
                editmode: false,
                auctions : {},
                form: new Form({
                    id : '',
                    user_organizer: '',
                    user_seller: '',
                    category: '',
                    name: '',
                    category_id: '',
                    organizer_id: '',
                    seller_id: '',
                    auction_number: '',
                    starts_at: '',
                    ends_at: '',
                    price_start: '',
                    price_min: '',
                    requisites: '',
                    manager_phone: '',
                    info: '',
                    additional_info: '',
                    status: '',
                    deposit: '',
                }),
                users: [],
                categories: [],

                autocompleteItems: [],
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();

              axios.get('/api/front/auction_front?page=' + page).then(({ data }) => (this.auctions = data.data));

              this.$Progress.finish();
          },
          loadAuctions(){

            // if(this.$gate.isAdmin()){
              axios.get("/api/front/auction_front").then(({ data }) => (this.auctions = data.data));
            // }
          },
          loadUsers(){
              axios.get("/api/user/list").then(({ data }) => (this.users = data.data));
          },
          loadCategories(){
              axios.get("/api/category/list").then(({ data }) => (this.categories = data.data));
          }
        },
        created() {
            this.$Progress.start();

            this.loadAuctions();
            this.loadUsers();
            this.loadCategories();

            this.$Progress.finish();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        computed: {
          filteredItems() {
            return this.autocompleteItems.filter(i => {
              return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
          },
        },
    }
</script>
