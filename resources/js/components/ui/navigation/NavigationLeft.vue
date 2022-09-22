<template>
  <div>
    <v-card v-if="loadingComponent == true">
      <v-skeleton-loader
        v-for="n in 5"
        :key="n"
        class="mx-auto"
        max-width="100%"
        type="list-item-three-line"
      ></v-skeleton-loader>
    </v-card>
    <v-list v-else dense rounded class="nav-left">
      <!-- Navigation Items -->
      <div v-for="item in commonNav" :key="item.title">
        <v-list-item :to="item.location" class="pl-10" active-class="secondary">
          <v-list-item-action class="mr-3">
            <v-icon small color="dark">{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content class="">
            <v-list-item-title v-text="item.title"> </v-list-item-title>
          </v-list-item-content>
          <!-- Notification Info here -->
        </v-list-item>
      </div>
      <v-divider class="my-2"></v-divider>
      <div v-for="item in secondaryNav" :key="item.title">
        <v-list-item :to="item.location" class="pl-10" active-class="secondary">
          <v-list-item-action class="mr-3">
            <v-icon small color="dark">{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content class="">
            <v-list-item-title v-text="item.title"> </v-list-item-title>
          </v-list-item-content>
          <!-- Notification Info here -->
        </v-list-item>
      </div>
      <v-divider class="my-2"></v-divider>
      <a href="https://gagconnect.gagroup.net/" target="_blank">
        <v-img max-width="250" src="/images/gag-connect-hive2.jpg"></v-img
      ></a>
      <v-divider class="my-2"></v-divider>
      <a class="speak-up" href="https://speakup.gagroup.net/" target="_blank">
        <v-img max-width="250" src="/images/speakup.png"></v-img
      ></a>
    </v-list>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loadingComponent: true,
      commonNav: [
        {
          title: "HOME",
          icon: "mdi-home-outline",
          location: "/d/home",
        },
        {
          title: "Feed",
          icon: "mdi-newspaper-variant-outline",
          location: "/d/feed",
        },
        {
          title: "NEWS & ARTICLES",
          icon: "mdi-account",
          location: "/d/news-articles",
        },
        {
          title: "BRANDS",
          icon: "mdi-account",
          location: "/d/brands",
        },
        {
          title: "COLEAGUES",
          icon: "mdi-account",
          location: "/d/colleagues",
        },
        {
          title: "TRAINING VIDEOS",
          icon: "mdi-account",
          location: "/d/training-videos",
        },
        {
          title: "CAREERS",
          icon: "mdi-account",
          location: "/d/careers",
        },
      ],
      secondaryNav: [
        {
          title: "BIRTHDAYS",
          icon: "mdi-home-outline",
          location: "/d/birthdays",
        },
        {
          title: "POLLS",
          icon: "mdi-account",
          location: "/d/polls",
        },
        {
          title: "EVENTS",
          icon: "mdi-account",
          location: "/d/events",
        },
        {
          title: "MARKETPLACE",
          icon: "mdi-account",
          location: "/d/marketplace",
        },
      ],
    };
  },
  created() {
    setTimeout(() => {
      this.loadingComponent = false;
    }, 300);
  },
};
</script>
