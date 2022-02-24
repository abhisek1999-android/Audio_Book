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

## Find the music generated file named "output220220223182443521.mp3"
##
## Method for OCR operation
```python

def async_detect_document(self,gcs_source_uri, gcs_destination_uri):
        """OCR with PDF/TIFF as source files on GCS"""
        import json
        import re
        from google.cloud import vision
        from google.cloud import storage
        mime_type = 'application/pdf'
        batch_size = 2
        client = vision.ImageAnnotatorClient()
        feature = vision.Feature(
            type_=vision.Feature.Type.DOCUMENT_TEXT_DETECTION)
        gcs_source = vision.GcsSource(uri=gcs_source_uri)
        input_config = vision.InputConfig(
            gcs_source=gcs_source, mime_type=mime_type)
        gcs_destination = vision.GcsDestination(uri=gcs_destination_uri)
        output_config = vision.OutputConfig(
           gcs_destination=gcs_destination, batch_size=batch_size)
        async_request = vision.AsyncAnnotateFileRequest(
            features=[feature], input_config=input_config,
            output_config=output_config)
        operation = client.async_batch_annotate_files(
            requests=[async_request])
        print('Waiting for the operation to finish.\n')
        operation.result(timeout=420)
        # Once the request has completed and the output has been
        # written to GCS, we can list all the output files.
        storage_client = storage.Client()
        match = re.match(r'gs://([^/]+)/(.+)', gcs_destination_uri)
        bucket_name = match.group(1)
        prefix = match.group(2)
        bucket = storage_client.get_bucket(bucket_name)
        # List objects with the given prefix, filtering out folders.
        blob_list = [blob for blob in list(bucket.list_blobs(
            prefix=prefix)) if not blob.name.endswith('/')]
        print('Output files:')
        for blob in blob_list:
            print(blob.name)
        # Process the first output file from GCS.
        # Since we specified batch_size=2, the first response contains
        # the first two pages of the input file.
        output = blob_list[0]
        json_string = output.download_as_string()
        response = json.loads(json_string)
        # The actual response for the first page of the input file.
        first_page_response = response['responses'][0]
        annotation = first_page_response['fullTextAnnotation']
        # Here we print the full text from the first page.
        # The response contains more information:
        # annotation/pages/blocks/paragraphs/words/symbols
        # including confidence scores and bounding boxes
        print('Full text:\n')
        print(annotation['text'])
 ```

## Method for Voice Model operation

```python

    def generate_voice(self,text):
        client = texttospeech_v1.TextToSpeechClient()
        voice_list = []
        for voice in client.list_voices().voices:
            voice_list.append([voice.name, voice.language_codes[0], voice.ssml_gender, voice.natural_sample_rate_hertz])
        df_voice_list = pd.DataFrame(voice_list, columns=['name', 'language code', 'ssml gender', 'hertz rate']).to_csv('Voice List.csv', index=False)

        # Set the text input to be synthesized
        # text = 'The habit of saving is itself an education; it fosters every virtue, teaches self-denial, cultivates the sense of order, trains to forethought, and so broadens the mind. By T.T.Munger'
        synthesis_input = texttospeech_v1.SynthesisInput(text=text)
        voice = texttospeech_v1.VoiceSelectionParams(
            language_code="en-in", ssml_gender=texttospeech.SsmlVoiceGender.FEMALE
        )
        # Select the type of audio file you want returned
        audio_config = texttospeech_v1.AudioConfig(
            # https://cloud.google.com/text-to-speech/docs/reference/rpc/google.cloud.texttospeech.v1#audioencoding
            audio_encoding=texttospeech_v1.AudioEncoding.MP3
        )
        # Perform the text-to-speech request on the text input with the selected
        # voice parameters and audio file type
        response = client.synthesize_speech(
            input=synthesis_input, voice=voice, audio_config=audio_config
        )
        # The response's audio_content is binary.
        from datetime import datetime
        (dt, micro) = datetime.utcnow().strftime('%Y%m%d%H%M%S.%f').split('.')
        dt = "%s%03d" % (dt, int(micro) / 1000)
        music_file_name="output"+dt+".mp3"
        with open(music_file_name, "wb") as out:
            # Write the response to the output file.
            out.write(response.audio_content)
            print('\nAudio content written to file')
        self.upload_music_file(music_file_name)
        
  ```
## UI Screen shot
![Screenshot (653)](https://user-images.githubusercontent.com/67363661/155581511-5c8b926d-b531-4927-95b7-0184d2a70c6c.png)

## Observation
>This model can work with simple pdf file, for large file which contains more images that model may not work properly and generated voice model may contain irrelevant words in it. 

