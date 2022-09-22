<template>
  <v-main class="grey lighten-3 mt-5 px-3">
    <v-container style="width: 100%; max-width: 1366px" class="mx-auto">
      <v-row>
        <v-col cols="12" md="3" sm="12">
          <navigation-left></navigation-left>
        </v-col>
        <v-col cols="12" md="6" sm="12">
          <div v-if="pageLoading == true">
            <v-card class="rounded-lg elevation-0 pb-5 mb-2">
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
          <div v-else>
            <v-card class="rounded-lg elevation-0 pa-5 pb-2 mb-3">
              <div class="d-flex align-flex-start justify-space-between mb-5">
                <avatar
                  :user="articleData.users"
                  :meta="{ status: true, post_date: articleData.created_at }"
                ></avatar>
                <div style="width: 110px" class="text-right">
                  <v-chip small>{{ typepost[articleData.type] }}</v-chip>
                </div>
              </div>
              <div class="text-body-1 mb-2">
                {{ articleData.title }}
              </div>
              <div
                v-if="articleData.images && articleData.images[0]"
                class="feature-image"
              >
                <v-img
                  :src="`${$baseUrl + '/file/' + articleData.images[0].path}`"
                ></v-img>
              </div>
              <v-divider
                class="mt-3 pb-2"
                style="border-color: #f1f1f1"
              ></v-divider>
              <div class="d-flex">
                <v-btn
                  class="mr-1"
                  text
                  small
                  @click="handleLike(articleData, index)"
                >
                  <v-icon small>{{
                    articleData.likes && articleData.likes.length > 0
                      ? likeIcon[1]
                      : likeIcon[0]
                  }}</v-icon>
                  <span v-if="articleData.likes_count > 0"
                    >({{ articleData.likes_count }})</span
                  >
                </v-btn>
                <v-btn text small
                  >comment
                  <span>({{ articleData.comments.length }})</span></v-btn
                >
              </div>
            </v-card>
          </div>
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
import { mapState } from "vuex";
import ContentRender from "../../ui/content/render/ContentRender.vue";
import NavigationLeft from "../../ui/navigation/NavigationLeft";
import NavigationRight from "../../ui/navigation/NavigationRight";
export default {
  components: {
    NavigationLeft,
    NavigationRight,
    ContentRender,
  },
  data() {
    return {
      likeIcon: ["mdi-thumb-up-outline", "mdi-thumb-up"],
      sbOptions: {},
      pageLoading: true,
      articleData: {},
      typepost: {
        post: "News & Article",
        event: "Event",
        poll: "Polls",
        training: "Training",
      },
    };
  },
  computed: {
    ...mapState(["singleArticle"]),
  },
  methods: {
    handleLike: function (item, index) {},
    async setArticle() {
      await this.$store.dispatch("fetchSingleArticle", this.$route.params.id);
    },
  },
  created() {
    console.log("this.articleData", this.articleData);
    // check if vuex has content otherwise fetch
    if (this.singleArticle.articleObject.hasOwnProperty("id")) {
      this.articleData = this.$store.state.singleArticle.articleObject;
      this.pageLoading = false;
    } else {
      this.setArticle().then(() => {
        this.articleData = this.$store.state.singleArticle.articleObject;
        this.pageLoading = false;
        console.log("setArticle", this.articleData);
      });
    }
    // console.log("computed", this.$store.state.singleArticle.articleObject);
  },
};
</script>

<style>
</style>
