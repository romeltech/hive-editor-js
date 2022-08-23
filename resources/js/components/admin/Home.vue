<template>
  <v-main class="grey lighten-3 mt-5 px-3">
    <v-container style="width: 100%; max-width: 1366px" class="mx-auto">
      <v-row>
        <v-col cols="12" sm="12" md="3">
          <!-- fixed-position -->
          <v-sheet rounded="lg" min-height="268" class="">
            <navigation-left></navigation-left>
          </v-sheet>
        </v-col>

        <v-col cols="12" md="6" sm="12">
          <v-sheet class="mx-auto pt-10 px-10 pb-2 main-content" rounded="lg">
            <div class="feature-image">
              <img src="/images/gag-connect-hive2.jpg" />
            </div>
            <div class="content-title text-h6">
              Welcome to Hive <small>News &amp; Articales</small>
            </div>
            <div class="section-content text-caption">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque turpis risus, fermentum id nibh non, aliquet pharetra
              ipsum. Nunc placerat in risus id facilisis. In rutrum faucibus
              dapibus. Aenean sodales est dui, quis varius mi egestas eget. Duis
              commodo nisi eu lorem fringilla sagittis. Nam ac arcu sit amet
              erat accumsan porttitor eget semper nibh. Proin elit erat,
              volutpat eu sapien sit amet, fringilla faucibus ligula. Proin
              elementum eget ex ut volutpat.
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
              <div class="post-datetime">August 5, 2022</div>
            </div>
            <v-divider></v-divider>
            <div class="section-comment">
             <v-text-field  
                v-model="message"
                  :append-outer-icon="message ? 'mdi-send' : ''"
                  :prepend-icon="icon"
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

            <v-sheet class="mx-auto pt-10 px-10 pb-2 main-content" rounded="lg">
            <div class="feature-image"></div>
            
            <div class="content-title text-h6">
              Welcome to Hive <small>News &amp; Articales</small>
            </div>
            <div class="section-content text-caption">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque turpis risus, fermentum id nibh non, aliquet pharetra
              ipsum. Nunc placerat in risus id facilisis. In rutrum faucibus
              dapibus. Aenean sodales est dui, quis varius mi egestas eget. Duis
              commodo nisi eu lorem fringilla sagittis. Nam ac arcu sit amet
              erat accumsan porttitor eget semper nibh. Proin elit erat,
              volutpat eu sapien sit amet, fringilla faucibus ligula. Proin
              elementum eget ex ut volutpat.
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
              <div class="post-datetime">August 5, 2022</div>
            </div>
            <v-divider></v-divider>
            <div class="section-comment">
             <v-text-field  
                v-model="message"
                  :append-outer-icon="message ? 'mdi-send' : ''"
                  :prepend-icon="icon"
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
    };
  },
  methods: {
     sendMessage () {
        this.resetIcon()
        this.clearMessage()
      },
      clearMessage () {
        this.message = ''
      },
  },
  
};
</script>