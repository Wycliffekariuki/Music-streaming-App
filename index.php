<?php
require_once 'Connection.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/Style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="Assets/Script.js"></script>
    <script src="Assets/code.jquery.com_jquery-3.7.0.min.js"></script>
    <style>
        header .song-side::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 300px;
            background: url(assets/img-5-6.png);
            z-index: -1;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            box-shadow: 0px 8px 20px -5px #111727;
        }
        .item li img{

        }
    </style>
</head>

<body>
    <script>
        // masterPlay.classList.remove('bi-play-fill');
        // masterPlay.classList.add('bi-pause-fill');

        $(document).ready(function () {
            var isPlaying = false;
            var music = null;
            var songs = []; // Array to store song elements
            var currentSongIndex = -1; // Index of the current song in the array
            // var seekBar = $("#seek");
            // var currentTime = $("#currentStart");
            // var totalTime = $("#currentEnd");

            // Function to play the current song
            function playCurrentSong() {
                if (currentSongIndex >= 0 && currentSongIndex < songs.length) {
                    if (!music || music.src !== songs[currentSongIndex]) {
                        if (music) {
                            music.pause();
                        }
                        music = new Audio(songs[currentSongIndex]);
                        music.play();
                        isPlaying = true;
                        $("#masterPlay").removeClass('bi-pause-fill').addClass('bi-play-fill');
                        $("#masterPlay").removeClass('bi-play-fill').addClass('bi-pause-fill');
                        $(".wave1").addClass('wavecrack');
                    } else if (music.paused) {
                        music.play();
                        isPlaying = true;
                        $(this).removeClass('bi-pause-fill').addClass('bi-play-fill');
                        $(".wave1").addClass('wavecrack');
                        $("#masterPlay").removeClass('bi-play-fill').addClass('bi-pause-fill');
                    } else {
                        music.pause();
                        isPlaying = false;
                        $(".wave1").removeClass('wavecrack');

                    }
                }
            }

            // Double-click to add song to array and play
            $(".songitem").dblclick(function () {
                var elementId = $(this).attr('id');
                songs.push(elementId);
                currentSongIndex = songs.length - 1; // Set current song to the newly added song
                var imgTag = $(this).find("img");
                var h5 = $(this).find("h5");
                var songName = h5.contents().filter(function () {
                    return this.nodeType === 3;
                }).text().trim();
                var subtitle = h5.find('.subtitle').text();
                // alert (subtitle);
                var img = imgTag.attr("src");
                $("#poster-master-play").attr("src", img);
                $('#master_song_name').contents().filter(function () {
                    return this.nodeType === 3; // Filter out text nodes
                }).first().replaceWith(document.createTextNode(songName)); $("#master_song_name").find("#master_artist_name").text(subtitle);
                $("#master_artist_name").text(subtitle);

                playCurrentSong();
            });

            // MasterPlay button functionality
            $("#masterPlay").click(function () {
                if (music) {
                    if (isPlaying) {
                        music.pause();
                        isPlaying = false;
                        $(this).removeClass('bi-pause-fill').addClass('bi-play-fill');
                        $(".wave1").removeClass('wavecrack');
                    } else {
                        music.play();
                        isPlaying = true;
                        $(this).removeClass('bi-play-fill').addClass('bi-pause-fill');
                        $(".wave1").addClass('wavecrack');
                    }
                }
            });

            // Skip button functionality
            $("#next").click(function () {
                currentSongIndex++;
                if (currentSongIndex >= songs.length) {
                    currentSongIndex = 0; // Loop back to the first song
                }
                playCurrentSong();
            });

            // Previous button functionality
            $("#back").click(function () {
                currentSongIndex--;
                if (currentSongIndex < 0) {
                    currentSongIndex = songs.length - 1; // Wrap around to the last song
                }
                playCurrentSong();
            });

            $(".playlistPlay").click(function () {
                var elementId = $(this).attr('id');
                // alert (elementId);
                songs.push(elementId);
                currentSongIndex = songs.length - 1; // Set current song to the newly added song
                var listItem = $(this).closest(".songItem");
                var imgTag = listItem.find("img");
                var img = imgTag.attr("src");
                var h5 = listItem.find("h5");
                var songName = h5.contents().filter(function () {
                    return this.nodeType === 3;
                }).text().trim();
                var subtitle = h5.find('.subtitle').text();
                // alert (subtitle);

                $("#poster-master-play").attr("src", img);
                $('#master_song_name').contents().filter(function () {
                    return this.nodeType === 3; // Filter out text nodes
                }).first().replaceWith(document.createTextNode(songName)); $("#master_song_name").find("#master_artist_name").text(subtitle);
                $("#master_artist_name").text(subtitle);
                playCurrentSong();
            })
            function isMediaPlaying() {
                return !music.paused && !music.ended;
            }
            setInterval(function () {
                if (music !== null) {
                    if (isMediaPlaying()) {
                        console.log("Song is playing!!");
                        console.log("music:", music); // Debugging statement

                        if (music) {
                            var seekbar = $('#seek')[0]; // Get the seekbar input element
                            console.log("seekbar:", seekbar); // Debugging statement

                            var audioDuration = 300; // Example duration in seconds (replace with actual duration)

                            music.addEventListener('timeupdate', function () {
                                var currentTime = music.currentTime;
                                var progress = (currentTime / music.duration) * 100;

                                $('#currentStart').text(formatTime(currentTime)); // Update start timestamp
                                $('#currentEnd').text(formatTime(music.duration)); // Update end timestamp

                                seekbar.value = progress; // Update seekbar position
                            });

                            // Update audio playback position based on seekbar input
                            seekbar.addEventListener('input', function () {
                                var progress = seekbar.value;
                                var currentTime = (progress / 100) * music.duration;

                                music.currentTime = currentTime; // Seek to the selected position
                            });

                            // Function to format time in minutes and seconds
                            function formatTime(seconds) {
                                var minutes = Math.floor(seconds / 60);
                                var remainingSeconds = Math.floor(seconds % 60);
                                return padZero(minutes) + ':' + padZero(remainingSeconds);
                            }

                            // Function to pad zero for single-digit numbers
                            function padZero(number) {
                                return (number < 10 ? '0' : '') + number;
                            }
                        } else {
                            console.log("Global 'music' variable is null or undefined.");
                        }
                    } else {
                        console.log("Song is not playing!!");
                        $("#masterPlay").removeClass('bi-pause-fill').addClass('bi-play-fill');
                        $(".wave1").removeClass('wavecrack');
                    }
                    const seekRange = document.getElementById("seek");

                    // Update audio.currentTime when the range input changes
                    seekRange.addEventListener("input", () => {
                        const percent = seekRange.value / seekRange.max;
                        music.currentTime = percent * music.duration;
                    });

                    // Update progress bar color as the music plays
                    music.addEventListener("timeupdate", () => {
                        const percent = music.currentTime / music.duration;
                        const gradientColor = `linear-gradient(to right, green 0%, green ${percent * 100}%, #DEE2E6 ${percent * 100}%, #DEE2E6 100%)`;
                        seekRange.style.background = gradientColor;
                    });
                }

                const volumeRange = document.getElementById("vol");

                // Update audio volume when the range input changes
                volumeRange.addEventListener("input", () => {
                    const volume = volumeRange.value / 100; // Convert to a value between 0 and 1
                    music.volume = volume;

                    // Dynamically change the progress bar color
                    const gradientColor = `linear-gradient(to right, green 0%, green ${volume * 100}%, #DEE2E6 ${volume * 100}%, #DEE2E6 100%)`;
                    volumeRange.style.background = gradientColor;
                });
            }, 1000);


            const volumeRange = document.getElementById("vol");

                const gradientColor = `linear-gradient(to right, green 0%, green ${0.3 * 100}%, #DEE2E6 ${0.3 * 100}%, #DEE2E6 100%)`;
                volumeRange.style.background = gradientColor;
                $("#camilaPlay").click(function() {
                songs.push("Assets/songs/audio-1.mp3");
                currentSongIndex = songs.length - 1;
                
                $("#poster-master-play").attr("src", "Assets/songimages/img-1.png");
                $('#master_song_name').contents().filter(function () {
                    return this.nodeType === 3; // Filter out text nodes
                }).first().replaceWith(document.createTextNode("Liar")); $("#master_song_name").find("#master_artist_name").text("Camila Cabello");
                $("#master_artist_name").text("Camila Cabello");
                    playCurrentSong();
                });



            });




    </script>
</body>
<header>
    <div class="menu-side">
        <h1>Playlist</h1>
        <div class="playlist">
            <h4 class="active"><span></span><i class="bi bi-music-note-beamed"></i> Playlist</h4>
            <h4><span></span><i class="bi bi-music-note-beamed"></i> Last Listening</h4>
            <h4><span></span><i class="bi bi-music-note-beamed"></i> Recommended</h4>
        </div>
        <div class="menu-song">
            <?php
            $query = "SELECT * FROM Songs";
$result = pg_query($dbcon, $query);

if (!$result) {
    echo "No songs found.\n";
    exit;
} else {
    $row_count = 0; // Initialize row counter

    while ($row = pg_fetch_assoc($result)) {
        if ($row_count >= 6) { // Limit to 6 rows
            break; // Exit the loop when 6 rows are reached
        }

        $artist_id = $row['artist_id'];
        $query_1 = "SELECT Artist_name FROM Artists WHERE Artist_id= $artist_id";
        $result_1 = pg_query($dbcon, $query_1);
        $artist_name = pg_fetch_result($result_1, 0, 'Artist_name');

        $hstoreData = pg_unescape_bytea($row['song_attr']);
        $parsedData = [];
        $pairs = explode(',', $hstoreData); // Split by commas
        foreach ($pairs as $pair) {
            list($key, $value) = explode('=>', $pair);
            $parsedData[trim($key, '" ')] = trim($value, '" ');
        }

        $urlKey = 'imgpath';
        $urlKey2 = 'path';
        $url = isset($parsedData[$urlKey]) ? htmlspecialchars_decode($parsedData[$urlKey]) : null;
        $path = isset($parsedData[$urlKey2]) ? htmlspecialchars_decode($parsedData[$urlKey2]) : null;

        echo '<li id="';
        echo $path;
        echo '" class="songitem">';
        echo '<span>01</span>';
        echo '<img src="';
        echo $url;
        echo '" alt="';
        echo ($row['song_name']);
        echo '">';
        echo '<h5>';
        echo $row['song_name'];
        echo '<div class="subtitle">';
        echo $artist_name;
        echo '</div>';
        echo '</h5>';
        echo '<i class="bi playlistPlay bi-play-circle-fill" id="1"></i>';
        echo '</li>';

        $row_count++; // Increment row counter
    }
}

            ?>
            <!--
            <li class="songitem">
                <span>01</span>
                <img src="assets/img-2.jpg" alt="Camila">
                <h5>
                    Liar
                    <div class="subtitle">Camila Cabello</div>
                </h5>
                <i class="bi playlistPlay bi-play-circle-fill" id="1"></i>
            </li>
            <li class="songitem">
                <span>02</span>
                <img src="assets/img-2.jpg" alt="Camila">
                <h5>
                    Liar
                    <div class="subtitle">Camila Cabello</div>
                </h5>
                <i class="bi playlistPlay bi-play-circle-fill" id="2"></i>
            </li>
            <li class="songitem">
                <span>03</span>
                <img src="assets/img-2.jpg" alt="Camila">
                <h5>
                    Liar
                    <div class="subtitle">Camila Cabello</div>
                </h5>
                <i class="bi playlistPlay bi-play-circle-fill" id="3"></i>
            </li>
            <li class="songitem">
                <span>04</span>
                <img src="assets/img-2.jpg" alt="Camila">
                <h5>
                    Liar
                    <div class="subtitle">Camila Cabello</div>
                </h5>
                <i class="bi playlistPlay bi-play-circle-fill" id="4"></i>

            </li>
            <li class="songitem">
                <span>05</span>
                <img src="assets/img-2.jpg" alt="Camila">
                <h5>
                    Liar
                    <div class="subtitle">Camila Cabello</div>
                </h5>
                <i class="bi playlistPlay bi-play-circle-fill" id="5"></i>

            </li>
            <li class="songitem">
                <span>06</span>
                <img src="assets/img-2.jpg" alt="Camila">
                <h5>
                    Liar
                    <div class="subtitle">Camila Cabello</div>
                    <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                </h5>
            </li>
        -->

        </div>
    </div>
    <div class="song-side">
        <nav>
            <ul>
                <li>Discover <span></span></li>
                <li>MY LIBRARY</li>
                <li>RADIO</li>
            </ul>
            <div class="search">
                <i class="bi bi-search"></i>
                <input type="text" name="" id="" placeholder="Search Music...">
            </div>
            <div class="user">
                <img src="Assets/img-3.png" alt="user" tittle="KAEMURD">
            </div>
        </nav>
        <div class="content">
            <h1>Camila Cabello</h1>
            <p> Oh no there you go making me a Liar
                <br>
                got me begging you for more
            </p>
            <div class="buttons">
                <button id="camilaPlay">Play</button>
                <button>Follow</button>
            </div>
        </div>
        <div class="popular-song">
            <div class="h4">
                <h4>Popular Song</h4>
                <div class="btn">
                    <i id="left-scroll" class="bi bi-arrow-left-short"></i>
                    <i id="right-scroll" class="bi bi-arrow-right-short"></i>
                </div>
            </div>
            <div class="pop-song">
                <?php
                $result1 = pg_query($dbcon, $query);
                $result2 = pg_query($dbcon, $query);
                
                if (!$result1) {
                    echo "No songs found.\n";
                    exit;
                } else {
                    $num_rows = pg_num_rows($result1);
                
                    if ($num_rows > 6) {
                        pg_result_seek($result1, 6); // Move the result pointer to the 7th row
                        while ($row = pg_fetch_assoc($result1)) {
                            $artist_id = $row['artist_id'];
                            $query_1 = "SELECT Artist_name FROM Artists WHERE Artist_id= $artist_id";
                            $result_1_1 = pg_query($dbcon, $query_1);
                            $artist_name = pg_fetch_result($result_1_1, 0, 'Artist_name');
                
                            $hstoreData = pg_unescape_bytea($row['song_attr']);
                            $parsedData = [];
                            $pairs = explode(',', $hstoreData); // Split by commas
                            foreach ($pairs as $pair) {
                                list($key, $value) = explode('=>', $pair);
                                $parsedData[trim($key, '" ')] = trim($value, '" ');
                            }
                
                            $urlKey = 'imgpath';
                            $urlKey2 = 'path';
                            $url = isset($parsedData[$urlKey]) ? htmlspecialchars_decode($parsedData[$urlKey]) : null;
                            $path = isset($parsedData[$urlKey2]) ? htmlspecialchars_decode($parsedData[$urlKey2]) : null;
                
                            echo ' <li class="songItem">
                                <div class="img-play">
                                    <img src="';
                            echo $url;
                            echo '" alt="';
                            echo $artist_name;
                            echo '">
                                    <i class="bi playlistPlay bi-play-circle-fill" id="';
                            echo $path;
                            echo '"></i>
                                </div>
                                <h5>
                                    ';
                            echo $row['song_name'];
                            echo '
                                    <br>
                                    <div class="subtitle">';
                            echo $artist_name;
                            echo '</div>
                                </h5>
                            </li>';
                        }
                    } else {
                        echo "Not enough rows available to start from row 7.";
                    }
                }
                
                ?>
                <!--
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
                <li class="songItem">
                    <div class="img-play">
                        <img src="Assets/img-2.jpg" alt="Camila Cabello">
                        <i class="bi playlistPlay bi-play-circle-fill" id="6"></i>
                    </div>
                    <h5>
                        Liar
                        <br>
                        <div class="subtitle">Camila Cabello</div>
                    </h5>
                </li>
        -->
            </div>
        </div>
        <div class="popular-artists">
            <div class="h4">
                <h4>Popular Artists</h4>
                <div class="btn">
                    <i id="left-scrolls" class="bi bi-arrow-left-short"></i>
                    <i id="right-scrolls" class="bi bi-arrow-right-short"></i>
                </div>
            </div>
            <div class="item">
                <li>
                    <img src="Assets/songimages/img-1.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-3.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-7.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-12.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-15.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-5.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-4.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-6.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
                <li>
                    <img src="Assets/songimages/img-11.png" alt="Camila Cabello" title="Camila Cabello">
                </li>
              
            </div>
        </div>
    </div>
    <div class="master-play">
        <div class="wave">
            <div class="wave1"></div>
            <div class="wave1"></div>
            <div class="wave1"></div>
        </div>
        <img src="assets/songimages/img-1.png" alt="Camila Cabello" id="poster-master-play">
        <h5 id="master_song_name">Liar <br>
            <div class="subtitle" id="master_artist_name">Camila Cabello</div>
        </h5>
        <div class="icon">
            <i class="bi bi-skip-start-fill" id="back"></i>
            <i class="bi bi-play-fill" id="masterPlay"></i>
            <i class="bi bi-skip-end-fill" id="next"></i>
        </div>
        <span id="currentStart">0:00</span>
        <div class="bar">
            <input type="range" id="seek" min="0" value="0" max="100">
            <div class="bar2" id="bar2"></div>
            <div class="dot"></div>
        </div>
        <span id="currentEnd">0:00</span>

        <div class="vol">
            <i class="bi bi-volume-down-fill"></i>
            <input type="range" id="vol" min="0" value="100" max="100">
            <div class="vol-bar"></div>
            <div class="dot" id="vol-dot"></div>

        </div>
    </div>
</header>

</html>