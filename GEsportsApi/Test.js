const teamImageNames = response.data.cargoquery;
const teamImages = {};

const imagePromises = teamImageNames.map(async (team) => {

    if (team.includes(team.title.Team)) {
        try {
            const response = await axios.get('https://lol.gamepedia.com/api.php', {
                params: {
                    action: 'query',
                    format: 'json',
                    prop: 'imageinfo',
                    iiprop: 'url',
                    titles: 'File:' + team.title.Image
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
                    teamImages[team.title.Team] = imageUrl;
                }
            }
        } catch (error) {
            console.error('Error fetching image URL:', error.message);
        }
    }
});