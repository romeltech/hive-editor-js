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
        <div class="v-tab" v-if="authenticated_user.role == 'admin'">
          <v-tab v-for="item in adminNav" :key="item.title" :to="item.location">
            {{ item.title }}
          </v-tab>
        </div>
      </v-tabs>

      <v-avatar
        class="hidden-sm-and-down"
        color="grey darken-1 shrink"
        size="32"
      ></v-avatar>
    </v-app-bar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      authenticated_user: this.$store.state.authUser.userObject,
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
