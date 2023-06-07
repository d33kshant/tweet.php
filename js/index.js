console.log("Script Loaded")
const times = document.getElementsByClassName("tweet-time")
for (let i = 0; i < times.length; i++) {
	let item = times[i];
	item.innerText = (new Date(item.innerText)).toDateString()
}

const tweets = document.getElementsByClassName("tweet-container")
for (let i = 0; i < tweets.length; i++) {
	const item = tweets[i]

	const id = item.getAttribute("id").slice(6)
	item.addEventListener('click', () => { window.location = `./tweet.php?id=${id}` })
}