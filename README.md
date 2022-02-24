# Audio_Book
This project takes pdf files and make the Audio books based on that pdf file.This project uses Google Vision API & Google Text to Speech AI.
This project basically takes a pdf file as an input , then "Google Vision Api " perfroms OCR (Optical character recognition) to that pdf and generates text and then those 
text is passed through Google Text to Speech Api to generate the voice model.
> [Importent Note: Those API's generates billing thats why in this project the voice model is restricted with 300 characters only. If you are ok with the billing this steps can help ]
in file MakeAudioBook.py
line 118,119 just remove 'text=data[:300]' that line and pass the complete "data'

## Requirements:
1.Google Colud Account (Billing Enabled)

2.HTML,Tailwind CSS,JS,PHP

3.XAMPP server

4.Composer

## Find the music generated file named 
