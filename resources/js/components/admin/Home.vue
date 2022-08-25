<template>
  <v-main class="grey lighten-3 mt-5 px-3">
       <v-container class="py-8" v-if="pageLoading == true">
      <v-row>
        <v-col cols="12">
          <v-skeleton-loader
            class="mx-auto"
            max-width="100%"
            type="list-item-avatar-three-line, image, article"
          ></v-skeleton-loader>
        </v-col>
      </v-row>
    </v-container>

    <v-container v-else style="width: 100%; max-width: 1366px" class="mx-auto">
      <v-row>
        <v-col cols="12" sm="12" md="3">
          <!-- fixed-position -->
          <v-sheet rounded="lg" min-height="268" class="">
            <navigation-left></navigation-left>
          </v-sheet>
        </v-col>

        <v-col cols="12" md="6" sm="12">
          <v-sheet v-for="(item) in items" :key="item.id" class="mx-auto pt-10 px-10 pb-2 main-content" rounded="lg">
            <div class="feature-image">
              <img src="/images/gag-connect-hive2.jpg" />
            </div>
            <div class="content-title text-h6">
                {{item.title}} <small>{{item.type == 'post' ? 'News & Articles' : ''}}</small>
            </div>
            <div class="section-content text-caption">
               
               <div
                        class="pa-4"
                        style="border: 1px solid #ccc"
                        v-html="item.content"
                      ></div>
            </div>
            <v-divider></v-divider>
            <div class="section-like d-flex">
              <div>
                <v-btn small text
                  ><v-icon small>mdi-thumb-up-outline</v-icon></v-btn
                >
                | <v-btn small text>comment(0)</v-btn>
              </div>
              <v-spacer></v-spacer>
              <div class="post-datetime">{{formatDateHelper(item.created_at)}}</div>
            </div>
            <v-divider></v-divider>
            <div class="section-comment">
             <v-text-field  
                v-model="message"
                  :append-outer-icon="message ? 'mdi-send' : ''"
                
                  outlined
                  clear-icon="mdi-close-circle"
                  clearable
                  dense
                  label="Enter your comment..."
                  type="text" 
                  @click:append-outer="sendMessage" 
                  @click:clear="clearMessage"
                ></v-text-field>
            </div>
          </v-sheet> 
            <v-row>
              <v-col class="text-center">
                <v-btn 
                  color="primary overlined"  
                  @click="loadMoreImage"
                  >Load More</v-btn
                >
                
              </v-col>
            </v-row>
        </v-col>

        <v-col cols="12" sm="12" md="3">
          <!--  -->
          <v-sheet rounded="lg" min-height="268" class="">
            <navigation-right></navigation-right>
          </v-sheet>
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>

<script>
import NavigationLeft from "./ui/navigation/NavigationLeft";
import NavigationRight from "./ui/navigation/NavigationRight";
export default {
  components: {
    NavigationLeft,
    NavigationRight,
  },
  data() {
    return {
      message: '',
      pageLoading: true,
      page: 1,
      showPerPage: 3,
      items: [],
    };
  },
  methods: {
    sendMessage: function() { 
      this.clearMessage();
      this.resetIcon();
    },
    clearMessage: function() {
      this.message = ''
    },

    async getAllData() {
      let response = "";
      let sort = "-"; 
        response = await axios.get(
          "/d/admin/posts-fetch/" +
            this.showPerPage +
            "/-/" +
            sort +
            "/?page=" +
            this.page
        );
     

      if (response.data) {
        response.data.data.forEach((i) => this.items.push(i)); 
      }
    },

    loadMoreImage: function () {
      this.page++;
      this.showPerPage = 5;
      this.getAllData();
    },
    
  },

  created() {  
      this.getAllData().then(() => {
        this.pageLoading = false;
      });
  },
  
};
</script>