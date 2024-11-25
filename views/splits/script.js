document.getElementById('btn-delete').addEventListener('click', deleteSplit);

async function deleteSplit() {
    if (window.confirm("Are you sure you want to delete this split?")) {
        const url = window.location.href.replace('view', 'delete');

        try {
            const response = await fetch(url, {
                method: "DELETE"
            });

            if (response.status !== 200) {
                throw new Error('Failed to delete');
            }

            window.location.replace('/splits');
        } catch (error) {
            console.error(error.message);
        }
    }
}