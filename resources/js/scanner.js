import QrScanner from 'qr-scanner';

export default function scannerPage(verifyUrl, checkInUrlTemplate) {
    return {
        result: null,
        cameraError: null,
        scanner: null,

        init() {
            const video = this.$refs.video;

            this.scanner = new QrScanner(
                video,
                (scanResult) => this.onScan(scanResult.data),
                {
                    highlightScanRegion: true,
                    highlightCodeOutline: true,
                    maxScansPerSecond: 4,
                }
            );

            this.scanner.start().catch(() => {
                this.cameraError = 'Could not access the camera. Check your browser permissions and try again.';
            });
        },

        async onScan(payload) {
            if (this.result) return;

            this.scanner?.pause();

            try {
                const { data } = await window.axios.post(verifyUrl, { payload });
                this.result = data;
            } catch (e) {
                this.result = { status: 'invalid' };
            }
        },

        async checkIn() {
            if (!this.result?.ticket) return;

            try {
                const url = checkInUrlTemplate.replace('__TICKET__', this.result.ticket.ticket_number);
                const { data } = await window.axios.post(url);
                this.result = data;
            } catch (e) {
                // leave current result state on error
            }
        },

        scanNext() {
            this.result = null;
            this.scanner?.start();
        },
    };
}
