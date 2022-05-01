# External Tools
- pdf2json
- Google Chrome

## Install pdf2json
git clone https://github.com/PellegrinoDurante/pdf2json.git
cd pdf2json
./configure
make
make install

## Install Google Chrome for Laravel Dusk
apt-get update
apt-get install wget -y
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
apt install ./google-chrome-stable_current_amd64.deb
