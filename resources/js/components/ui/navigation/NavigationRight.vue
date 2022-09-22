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
    <v-list v-else dense rounded>
      <h3 class="text-center">Congratulations!</h3>
      <v-divider class="my-2"></v-divider>
      <h5 class="text-center">Employee of the Year!</h5>
      <v-img width="230" src="/images/winner.webp"></v-img>
      <v-divider class="my-3"></v-divider>
      <div class="mx-2">
        <h5 class="header-birthday">TODAY'S BIRTHDAY</h5>

        <v-list class="nav-right" dense v-if="birthdays">
          <v-list-item class="pl-1" v-for="item in birthdays" :key="item.name">
            <v-list-item-action class="mr-3">
              <v-icon small color="red">{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content class="black--text">
              <v-list-item-title>{{ item.name }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </div>

      <v-divider class="my-2"></v-divider>
      <div class="mx-2">
        <h5 class="header-joiner">NEW JOINERS</h5>
        <v-list class="nav-right" dense v-if="joiners">
          <v-list-item class="pl-1" v-for="item in joiners" :key="item.name">
            <!-- <v-list-item-action class="mr-3">
              <v-icon small color="green">{{ item.icon }}</v-icon>
            </v-list-item-action> -->
            <v-list-item-content class="black--text">
              <v-list-item-title class="text-capitalize font-weight-bold">{{
                item.name
              }}</v-list-item-title>
              <div class="text-uppercase job-position">{{ item.position }}</div>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </div>
    </v-list>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loadingComponent: true,
      birthdays: [
        {
          name: "Steve ayala",
          icon: "mdi-cake",
        },
        {
          name: "Steve Ayala 1",
          icon: "mdi-cake",
        },
        {
          name: "Steve Ayala 2",
          icon: "mdi-cake",
        },
        {
          name: "Steve Ayala 3",
          icon: "mdi-cake",
        },
        {
          name: "Steve Ayala 4",
          icon: "mdi-cake",
        },
        {
          name: "Steve Ayala 5",
          icon: "mdi-cake",
        },
      ],
      joiners: [
        {
          name: "Steve Ayala",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 1",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 2",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 3",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 4",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 5",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 6",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
        },
        {
          name: "Steve Ayala 7",
          icon: "mdi-account-check",
          position:
            "GHASSAN ABOUD GROUP FZE - MANAGER - GOVERNMENT RELATIONS AD HUBS",
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
