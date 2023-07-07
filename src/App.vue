<template>
	<main>
		<v-row fluid>
			<v-col cols="12" class="mt-10">
				<v-card class="pa-0 ma-0" elevation="0">
					<v-text-field v-model="keyword" variant="solo-filled" flat label="Enter a keyword to view the most recent video." hide-details="auto" clearable @click:clear="showSearch = false" @keyup.enter="searchByKeyTag">
						<template v-slot:loader>
							<v-progress-linear :active="custom" :model-value="progress" :color="color" absolute height="7" indeterminate></v-progress-linear>
						</template>
						<template v-slot:append>
							<v-btn class="ma-0 pa-0 ml-n5" height="56" width="64" color="teal" rounded="0" dark variant="elevated" @click="searchByKeyTag">
								<v-icon size="x-large" icon="mdi-movie-search-outline" />
							</v-btn>
						</template>
					</v-text-field>
				</v-card>
			</v-col>
			<v-col cols="12" v-if="channel_on_view.channelId">
				<v-card elevation="0" class="mt-5" style="background-color: transparent;">
					<div class="d-inline-flex flex-no-wrap justify-space-between">
						<v-img class="align-end text-white" max-height="200" :src="channel_on_view.thumbnails.medium.url" />
						<div>
							<v-card-title> {{ channel_on_view.customUrl }}</v-card-title>
							<v-card-text class="text-justify">{{ channel_on_view.description }}</v-card-text>
						</div>
						<div>
							<v-menu>
								<template v-slot:activator="{ props }">
									<v-btn variant="plain" icon="mdi-dots-vertical" v-bind="props"></v-btn>
								</template>
								<v-list density="compact">
									<v-list-item link @click="addToLibrary">
										<template v-slot:prepend>
											<v-icon size="small" class="ma-3" color="teal" icon="mdi-book-plus"></v-icon>
										</template>
										<v-list-item-title>Save to library</v-list-item-title>
									</v-list-item>
									<v-list-item link @click="sycVideo">
										<template v-slot:prepend>
											<v-icon size="small" class="ma-3" color="teal" icon="mdi-sync"></v-icon>
										</template>
										<v-list-item-title>Sync video</v-list-item-title>
									</v-list-item>
								</v-list>
							</v-menu>
						</div>
					</div>
				</v-card>
			</v-col>
			<v-col cols="12" v-if="(channel_video_list.length + search_channel_video_list.length + channel_added_to_library.length) <= 0">
				<v-card elevation="0" class="pt-10" style="background-color: transparent;">
					<div class="d-inline-flex flex-no-wrap justify-space-between">
						<div>
							<v-card-title class="text-h5"> YouTube Channel's </v-card-title>
							<v-card-text class="text-justify">To this date, it is difficult to find high-level statistics on YouTube that paint a fair picture of the platform in its entirety. This study attempts to provide an overall characterization of YouTube, based on a random sample of channel and video data, by showing how video provision and consumption evolved over the course of the past 10 years. It demonstrates stark contrasts between video genres in terms of channels, uploads and views, and that a vast majority of on average 85% of all views goes to a small minority of 3% of all channels. The analytical results give evidence that older channels have a significantly higher probability to garner a large viewership, but also show that there has always been a small chance for young channels to become successful quickly, depending on whether they choose their genre wisely.</v-card-text>
						</div>
						<v-img height="300" class="mt-n10" src="/noresulticon.gif" cover />
					</div>
				</v-card>
			</v-col>
			<v-col cols="12" v-if="!showSearch && channel_added_to_library.length > 0 && !channel_on_view.channelId">
				<v-row>
					<v-col class="d-flex child-flex" v-for="(videoObject, i) in channel_added_to_library" :key="i" cols="12" sm="6" xs="12" lg="6" md="6">
						<libraryCard @getChannelVideo="libChannelVideo" :videoObject="videoObject" />
					</v-col>
				</v-row>
			</v-col>
			<v-col cols="12" v-if="!showSearch && channel_video_list.length > 0">
				<v-row>
					<v-col class="d-flex child-flex" v-for="(videoObject, i) in channel_video_list" :key="i" cols="12" sm="6" xs="12" lg="4" md="4">
						<itemCard @getChannelVideo="getChannelVideo" :videoObject="videoObject" />
					</v-col>
				</v-row>
			</v-col>
			<v-col cols="12" v-if="showSearch">
				<searchResult @getChannelVideo="getChannelInfo" v-for="(videoObject, i) in search_channel_video_list" :key="i" :videoObject="videoObject" />
			</v-col>
		</v-row>
	</main>
</template>
<script>
	import itemCard from "./components/ItemCard.vue";
	import searchResult from "./components/SearchResult.vue";
	import libraryCard from "./components/LibraryCard.vue";
	export default {
		components: {
			itemCard,
			searchResult,
			libraryCard
		},
		data: () => ({
			keyword: '',
			custom: false,
			api_key: 'AIzaSyBmbd93yBwY2BYtcI4L_5BizqqTZBk3Ve8',
			channel_video_list: [],
			channel_added_to_library: [],
			search_channel_video_list: [],
			showSearch: false,
			channel_on_view: {
				channelId: '',
				customUrl: '',
				title: '',
				description: '',
				publishedAt: '',
				country: '',
				contentDetails: {},
				thumbnails: {},
				statistics: {}
			}
		}),
		mounted() {
			this.checkLibrary();
		},
		computed: {
			progress() {
				return Math.min(100, this.keyword.length * 10)
			},
			color() {
				return ['error', 'warning', 'success'][Math.floor(this.progress / 40)]
			},
		},
		methods: {
			searchByKeyTag() {
				this.showSearch = true;
				this.search_channel_video_list = [];
				this.resetOnViewChannel();
				let scope = this;
				let http_request = 'https://youtube.googleapis.com/youtube/v3/search?part=snippet&maxResults=20&q=' + this.keyword + '&type=channel&key=' + this.api_key
				this.axios.get(http_request).then(function(response) {
					let items = response.data.items
					for (let x in items) {
						var obj = {
							kind: items[x].id.kind,
							videoId: items[x].id.videoId ? items[x].id.videoId : '',
							channelId: items[x].snippet.channelId,
							title: items[x].snippet.title,
							channelTitle: items[x].snippet.channelTitle,
							description: items[x].snippet.description,
							thumbnails: items[x].snippet.thumbnails,
							videoPublishedAt: ''
						};
						scope.search_channel_video_list.push(obj);
					}
				}).catch(function(error) {
					console.log(error);
				})
			},
			getChannelInfo(channelid) {
				this.showSearch = false;
				let scope = this;
				let http_request = 'https://youtube.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&id=' + channelid + '&key=' + this.api_key
				this.axios.get(http_request).then(function(response) {
					let item = response.data.items[0]
					scope.channel_on_view.channelId = item.id;
					scope.channel_on_view.customUrl = item.snippet.customUrl;
					scope.channel_on_view.title = item.snippet.title;
					scope.channel_on_view.description = item.snippet.description;
					scope.channel_on_view.publishedAt = item.snippet.publishedAt;
					scope.channel_on_view.country = item.snippet.country;
					scope.channel_on_view.contentDetails = item.contentDetails;
					scope.channel_on_view.thumbnails = item.snippet.thumbnails;
					scope.channel_on_view.statistics = item.statistics;
					scope.getChannelVideo(item.contentDetails.relatedPlaylists.uploads)
				}).catch(function(error) {
					console.log(error);
				})
			},
			getChannelVideo(playlistId, max_result = 50) { //api limit is 50
				this.channel_video_list = [];
				this.showSearch = false;
				let scope = this;
				let http_request = 'https://youtube.googleapis.com/youtube/v3/playlistItems?part=snippet%2CcontentDetails&maxResults=' + max_result + '&playlistId=' + playlistId + '&key=' + this.api_key
				this.axios.get(http_request).then(function(response) {
					let items = response.data.items
					for (let x in items) {
						var obj = {
							kind: items[x].kind,
							videoId: items[x].contentDetails.videoId,
							channelId: items[x].snippet.channelId,
							title: items[x].snippet.title,
							channelTitle: items[x].snippet.channelTitle,
							description: items[x].snippet.description,
							thumbnails: items[x].snippet.thumbnails,
							videoPublishedAt: items[x].contentDetails.videoPublishedAt
						};
						scope.channel_video_list.push(obj);
					}
				}).catch(function(error) {
					console.log(error);
				})
			},
			addToLibrary() {
				this.axios.post('/youtube_channels.php', this.channel_on_view).then(function(response) {
					console.log(response)
				}).catch(function(error) {
					console.log(error);
				})
			},
			checkLibrary() {
				let scope = this;
				this.axios.get('/youtube_channels.php').then(function(response) {
					let items = response.data
					if(!Array.isArray(items)){
						return
					}
					for (let x in items) {
						var obj = {
							channelId: items[x].channelId,
							title: items[x].title,
							country: items[x].country,
							customUrl: items[x].customUrl,
							description: scope.minimizeText(items[x].description, 100),
							publishedAt: items[x].publishedAt,
							contentDetails: JSON.parse(items[x].contentDetails),
							statistics: JSON.parse(items[x].statistics),
							thumbnails: JSON.parse(items[x].thumbnails),
						};
						scope.channel_added_to_library.push(obj);
					}
				}).catch(function(error) {
					console.log(error);
				})
			},
			libChannelVideo(videoObject) {
				this.channel_on_view = videoObject;
				let channelid = this.channel_on_view.channelId
				this.showSearch = false;
				this.channel_video_list = [];
				let scope = this;
				console.log(channelid);
				this.axios.get('/youtube_channel_videos.php?channelId=' + channelid).then(function(response) {
					let items = response.data
					if(!Array.isArray(items)){
						return
					}
					for (let x in items) {
						var obj = {
							kind: items[x].kind,
							videoId: items[x].videoId,
							channelId: items[x].channelId,
							title: items[x].title,
							channelTitle: items[x].channelTitle,
							description: items[x].description,
							thumbnails: items[x].thumbnails,
							videoPublishedAt: items[x].videoPublishedAt
						};
						scope.channel_video_list.push(obj)
					}
				}).catch(function(error) {
					console.log(error);
				})
			},
			minimizeText(value, count) {
				return value.slice(0, count) + (value.length > count ? "..." : "");
			},
			sycVideo() {
				let toSyncVideo = [];
				let scope = this;
				this.showSearch = false;
				let max_result = 50;
				let playlistId = this.channel_on_view.contentDetails.relatedPlaylists.uploads;
				let http_request = 'https://youtube.googleapis.com/youtube/v3/playlistItems?part=snippet%2CcontentDetails&maxResults=' + max_result + '&playlistId=' + playlistId + '&key=' + this.api_key
				this.axios.get(http_request).then(function(response) {
					let items = response.data.items
					for (let x in items) {
						var obj = {
							kind: items[x].kind,
							videoId: items[x].contentDetails.videoId,
							channelId: items[x].snippet.channelId,
							title: items[x].snippet.title,
							channelTitle: items[x].snippet.channelTitle,
							description: items[x].snippet.description,
							thumbnails: items[x].snippet.thumbnails,
							videoPublishedAt: items[x].contentDetails.videoPublishedAt
						};
						toSyncVideo.push(obj);
					}
					scope.axios.post('/sync_youtube_channel.php', toSyncVideo).then(function(response) {
						let items = response.data
						if(!Array.isArray(items)){
							return
						}
						scope.channel_video_list = [];
						for (let x in items) {
							var obj = {
								kind: items[x].kind,
								videoId: items[x].videoId,
								channelId: items[x].channelId,
								title: items[x].title,
								channelTitle: items[x].channelTitle,
								description: items[x].description,
								thumbnails: items[x].thumbnails,
								videoPublishedAt: items[x].videoPublishedAt
							};
							scope.channel_video_list.push(obj)
						}
					}).catch(function(error) {
						console.log(error);
					})
				}).catch(function(error) {
					console.log(error);
				})
			},
			resetOnViewChannel() {
				this.channel_on_view = {
					channelId: '',
					customUrl: '',
					title: '',
					description: '',
					publishedAt: '',
					country: '',
					contentDetails: {},
					thumbnails: {},
					statistics: {}
				}
			}
		}
	}
</script>
<style scoped>
	div.v-input__append {
		margin: 0px !important;
	}
</style>