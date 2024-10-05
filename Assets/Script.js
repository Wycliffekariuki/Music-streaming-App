/*
const music = new Audio('audio-1.mp3');

const songs = [
    {
        id: '1',
        songName: `Liar <br><div class="subtitle">Camila Cabello</div>`,
        poster: "img-2.jpg"
    },
    {
        id: '2',
        songName: `Juice World-Robbery <br><div class="subtitle">Juice World</div>`,
        poster: "img-6.jpg"
    }
];

Array.from(document.getElementsByClassName('songItem')).forEach((element, i) => {
    element.getElementsByTagName('img')[0].src = songs[i].poster;
    element.getElementsByTagName('h5')[0].innerHTML = songs[i].songName;
});

let masterPlay = document.getElementById('masterPlay');

masterPlay.addEventListener('click', () => {
    if (music.paused || music.currentTime <= 0) {
        music.play();
        masterPlay.classList.remove('bi-play-fill');
        masterPlay.classList.add('bi-pause-fill');
    } else {
        music.pause();
        masterPlay.classList.add('bi-play-fill');
        masterPlay.classList.remove('bi-pause-fill');
    }
});
*/