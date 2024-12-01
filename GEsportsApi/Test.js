import axios from 'axios';
import fs from 'fs';
import path from 'path';
import https from 'https';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function downloadImage(url, filepath) {
    return new Promise((resolve, reject) => {
        https.get(url, { rejectUnauthorized: false }, (response) => {
            if (response.statusCode !== 200) {
                reject(new Error(`Failed to download image: ${response.statusCode}`));
                return;
            }

            const fileStream = fs.createWriteStream(filepath);
            response.pipe(fileStream);

            fileStream.on('finish', () => {
                fileStream.close();
                resolve();
            });

            fileStream.on('error', (err) => {
                fs.unlink(filepath, () => reject(err));
            });
        }).on('error', (err) => {
            reject(err);
        });
    });
}

async function processTeamImages() {
    try {
        // First get the team data
        const cargoResponse = await axios.get('http://localhost:8001/AllTeamImages');
        const teams = cargoResponse.data;
        
        if (!Array.isArray(teams)) {
            throw new Error('Invalid API response format. Expected array of teams.');
        }

        const teamImages = {};
        const imageDir = path.join(__dirname, '..', 'storage', 'app', 'public', 'teamimages');

        // Create directory if it doesn't exist
        if (!fs.existsSync(imageDir)) {
            fs.mkdirSync(imageDir, { recursive: true });
        }

        const imagePromises = teams.map(async (team) => {
            if (team.OverviewPage && team.Image) {
                try {
                    const response = await axios.get('https://lol.gamepedia.com/api.php', {
                        params: {
                            action: 'query',
                            format: 'json',
                            prop: 'imageinfo',
                            iiprop: 'url',
                            titles: 'File:' + team.Image
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.data || !response.data.query || !response.data.query.pages) {
                        throw new Error('Unexpected API response');
                    }
                    const pages = response.data.query.pages;
                    for (const p in pages) {
                        if (pages[p].imageinfo) {
                            let imageUrl = pages[p].imageinfo[0].url;
                            imageUrl = imageUrl.split('.png')[0] + '.png';
                            teamImages[team.OverviewPage] = imageUrl;

                            // Download the image
                            const filepath = path.join(imageDir, `${team.OverviewPage}.png`);
                            if (!fs.existsSync(filepath)) {
                                console.log(`Downloading image for ${team.OverviewPage}...`);
                                await downloadImage(imageUrl, filepath);
                                console.log(`Successfully downloaded image for ${team.OverviewPage}`);
                            } else {
                                console.log(`Image for ${team.OverviewPage} already exists, skipping`);
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error fetching image URL for', team.OverviewPage, ':', error.message);
                }
            }
        });

        await Promise.all(imagePromises);
        console.log('All images processed');
        return teamImages;
    } catch (error) {
        console.error('Error in processTeamImages:', error.message);
        throw error;
    }
}

// Run the script
processTeamImages().catch(console.error);