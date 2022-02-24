from distutils.command.upload import upload
import os, io
from unicodedata import name
from google.cloud import texttospeech # outdated or incomplete comparing to v1
from google.cloud import texttospeech_v1
import pandas as pd            
import sys        

import sys
input=sys.argv[1]

os.environ['GOOGLE_APPLICATION_CREDENTIALS'] = r'<YOUR SERVICE KEY.JSON>'
class MakeAudio:
    def __init__(self) -> None:
        pass 
    def uplod_file(self,recieved_file_name):
        try: 
            import io
            from io import BytesIO
            from google.cloud import storage
        except Exception as e:
            print("Modules are missing")
        storage_client=storage.Client.from_service_account_json("<YOUR SERVICE KEY.JSON>")

        bucket =storage_client.get_bucket("<YOUR-BUCKET-NAME>")

        from datetime import datetime
        (dt, micro) = datetime.utcnow().strftime('%Y%m%d%H%M%S.%f').split('.')
        dt = "%s%03d" % (dt, int(micro) / 1000)
        filename="%s%s" % ('',"myFile"+dt+".pdf")

        blob=bucket.blob(filename)

        print("Got the file name : "+recieved_file_name+"\n")
        blob.upload_from_filename(recieved_file_name)

        
        print("file uploded\n")
        
        gcs_source='gs://<YOUR-BUCKET-NAME>/'+filename
        gcs_dest='gs://<YOUR-BUCKET-NAME>/DESTINATION'

        self.async_detect_document(gcs_source,gcs_dest)

    
    def async_detect_document(self,gcs_source_uri, gcs_destination_uri):
        """OCR with PDF/TIFF as source files on GCS"""
        import json
        import re
        from google.cloud import vision
        from google.cloud import storage

        # Supported mime_types are: 'application/pdf' and 'image/tiff'
        mime_type = 'application/pdf'

        # How many pages should be grouped into each json output file.
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
        data=annotation['text']
        text=data[:300]
        self.generate_voice(text)


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

    def upload_music_file(self,recieved_file_name):
        try: 
            import io
            from io import BytesIO
            from google.cloud import storage
        except Exception as e:
            print("Modules are missing")
        storage_client=storage.Client.from_service_account_json("<YOUR-SERVICE-KEY.JSON>")

        bucket =storage_client.get_bucket("<YOUR-PUBLIC-MUSIC-BUCKET-NAME>")

        from datetime import datetime
        (dt, micro) = datetime.utcnow().strftime('%Y%m%d%H%M%S.%f').split('.')
        dt = "%s%03d" % (dt, int(micro) / 1000)
        filename="%s%s" % ('',input.replace(".","")+dt+".mp3")

        blob=bucket.blob(filename)

        print("Got the file name : "+recieved_file_name+"\n")
        blob.upload_from_filename(recieved_file_name)

        
        print("file uploded\n")
    


mkA=MakeAudio()
mkA.uplod_file(input)
