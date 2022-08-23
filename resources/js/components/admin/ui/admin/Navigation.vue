<template>
  <div class="no-print">
    <v-navigation-drawer
      class="elevation-4"
      v-model="drawer"
      floating
      app
      width="250px"
      :src="`${$baseUrl + '/images/gag-2.png'}`"
      color="#000"
      dark
    >
      <div width="100%" class="text-center pa-3 no-print">
        <v-avatar size="150">
          <v-img :src="profileImagePath"></v-img>
        </v-avatar>
        <div class="overline white--text">
          {{ authenticated_user.name }}
        </div>
     
          <div>
            <v-btn small text link to="/d/user/account">
            <v-icon >mdi-account-lock-open</v-icon> 
           </v-btn>
          </div> 
         
      </div>
      <v-divider></v-divider>
      <v-list dense rounded>
        <!-- Navigation Items -->
        <!-- Common Nav -->
        <nav-item
          v-for="item in commonNav"
          :key="item.title"
          :nav="item"
        ></nav-item>
        
         
      </v-list>
    </v-navigation-drawer>
    <v-app-bar app color="white" dense class="elevation-gag no-print">
      <v-app-bar-nav-icon
        @click.stop="drawer = !drawer"
        color="primary"
      ></v-app-bar-nav-icon>
      <v-toolbar-title class="pl-1 mr-12 align-center d-flex">
        <v-img max-width="25" :src="`${$baseUrl + '/images/fav.png'}`"> </v-img>
        <span class="ml-2 title primary--text text-capitalize"
          >{{ authenticated_user.role }} Panel</span
        >
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-width="150"
        transition="slide-y-transition"
        offset-y
        :nudge-bottom="3"
      >
        <template v-slot:activator="{ on }">
          <v-btn text icon v-on="on">
            <v-avatar size="30">
              <img :src="profileImagePath" />
            </v-avatar>
          </v-btn>
        </template>
        <v-card>
          <v-list>
            <v-list-item>
              <v-list-item-avatar>
                <img :src="profileImagePath" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{
                  authenticated_user.name
                }}</v-list-item-title>
                <v-list-item-subtitle>{{
                  authenticated_user.email
                }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <v-divider></v-divider>
          <v-card-actions>
            <v-btn depressed v-on:click="logout" width="100%">Logout</v-btn>
          </v-card-actions>
        </v-card>
      </v-menu>
    </v-app-bar>
  </div>
</template>

<script>
import NavItem from "./NavItem";
export default {
  components: {
    NavItem,
  },
  data() {
    return {
      authenticated_user: this.$store.state.authUser.userObject,
      profileImagePath: this.$store.state.authUser.userObject.profile_image
        ? window.location.origin +
          "/file/" +
          this.$store.state.authUser.userObject.profile_image.path
        : window.location.origin + "/images/placeholder-user.png",
      drawer: true,
      menu: false,
      commonNav: [
        {
          title: "Home",
          icon: "mdi-home-outline",
          location: "/d/frontend",
        },
        {
          title: "News & Articles",
          icon: "mdi-file-document-multiple-outline",
          location: "/d/admin/medias"
        },
        {
          title: "Events",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/events"
        },
        {
          title: "Polls",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/polls"
        },
         {
          title: "Employees",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/employees"
        },
         {
          title: "Trainings",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/trainings"
        },
        {
          title: "Careers",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/careers"
        },
        {
          title: "Package Ads",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/package_ads"
        },
        {
          title: "Cashless",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/cashless"
        },
        {
          title: "Marketplace",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/marketplace"
        },
        {
          title: "Suggestions",
          icon: "mdi-file-document-multiple",
          location: "/d/admin/suggestions"
        },
        
      ],
      
    };
  },
  methods: {
    logout: function (event) {
      event.preventDefault();
      document.getElementById("logout-form").submit();
    },
  },
};
</script>
