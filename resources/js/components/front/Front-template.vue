<template>
  <v-main class="grey lighten-3 mt-5 px-3">
    <v-container style="width: 100%; max-width: 1366px" class="mx-auto">
      <v-row>
        <v-col cols="12" md="3" sm="12">
          <navigation-left></navigation-left>
        </v-col>
        <v-col cols="12" md="6" sm="12">
          <div v-if="pageLoading == true">
            <v-card
              v-for="n in 5"
              :key="n"
              class="rounded-lg elevation-0 pb-5 mb-2"
            >
              <v-skeleton-loader
                class="mx-auto"
                max-width="100%"
                type="list-item-avatar-three-line"
              ></v-skeleton-loader>
              <div class="px-5">
                <v-skeleton-loader
                  class="mx-auto"
                  max-width="100%"
                  type="image"
                ></v-skeleton-loader>
              </div>
            </v-card>
          </div>
          <div v-else>the content</div>
        </v-col>
        <v-col cols="12" md="3" sm="12">
          <navigation-right></navigation-right>
        </v-col>
      </v-row>
    </v-container>
    <snack-bar :snackbar-options="sbOptions"></snack-bar>
  </v-main>
</template>

<script>
import ContentRender from "../ui/content/render/ContentRender.vue";
import NavigationLeft from "../ui/navigation/NavigationLeft";
import NavigationRight from "../ui/navigation/NavigationRight";
export default {
  components: {
    NavigationLeft,
    NavigationRight,
    ContentRender,
  },
  data() {
    return {
      sbOptions: {},
      pageLoading: true,
    };
  },
  created() {
    setTimeout(() => {
      this.pageLoading = false;
    }, 300);
  },
};
</script>

<style>
</style>
