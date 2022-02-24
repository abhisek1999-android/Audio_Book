from google.cloud import storage
import os, io


print("hello")
# os.environ['GOOGLE_APPLICATION_CREDENTIALS'] = r'GoogleCloudKey_MyServiceAcct.json'
# def list_blobs(bucket_name):
#     """Lists all the blobs in the bucket."""
#     # bucket_name = "your-bucket-name"

#     storage_client = storage.Client()

#     # Note: Client.list_blobs requires at least package version 1.17.0.
#     blobs = storage_client.list_blobs(bucket_name)

#     for blob in blobs:
#         print(blob.name)
#         print(blob.path)

# list_blobs('audiobook-21022022')