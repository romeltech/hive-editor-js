<template>
  <div>
    <v-app-bar app color="white" flat>
      <v-avatar
        :color="$vuetify.breakpoint.smAndDown ? 'grey darken-1' : 'transparent'"
        size="32"
      ></v-avatar>
      <v-tabs centered class="ml-n9" color="grey darken-1">
        <v-tab v-for="item in commonNav" :key="item.title" :to="item.location">
          {{ item.title }}
        </v-tab>
        <div class="v-tab" v-if="authenticated_user.role == 'admin' || authenticated_user.role == 'moderator' || authenticated_user.role == 'superadmin'">
          <v-tab v-for="item in adminNav" :key="item.title" :to="item.location">
            {{ item.title }}
          </v-tab>
        </div>
      </v-tabs>

      
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
            <v-avatar size="30" color="blue-grey lighten-4">
              <img :src="profileImagePath" />
            </v-avatar>
          </v-btn>
        </template>
        <v-card>
          <v-list>
            <v-list-item>
              <v-list-item-avatar color="blue-grey lighten-4">
                <img :src="profileImagePath" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{
                  authenticated_user.profile.fullname
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
export default {
  data() {
    return {
      
      authenticated_user: this.$store.state.authUser.userObject,
      profileImagePath: this.$store.state.authUser.userObject.images.length > 0
        ? window.location.origin +
          "/file/" +
          this.$store.state.authUser.userObject.images[0].path
        : window.location.origin + "/images/placeholder-user.png",
      drawer: true,
      menu: false,
      commonNav: [
        {
          title: "HOME",
          icon: "mdi-home-outline",
          location: "/d/home",
        },
        {
          title: "PROBATION FORM",
          icon: "mdi-account",
          location: "/d/probation",
        },
        {
          title: "MY PERFORMACE",
          icon: "mdi-account",
          location: "/d/performance",
        },
        {
          title: "MY WALLET",
          icon: "mdi-account",
          location: "/d/wallet",
        },
      ],
      adminNav: [
        {
          title: "MODERATOR",
          icon: "mdi-account-group-outline",
          location: "/d/moderators",
        },
      ],
    };
  },
  methods: {
    printInitials: function (text) {
      return text
        .split(" ")
        .slice(0, 2)
        .join(" ")
        .split(" ")
        .map((n) => n[0])
        .join("");
    },
    logout: function (event) {
      event.preventDefault();
      document.getElementById("logout-form").submit();
    },
  },
};
</script>
