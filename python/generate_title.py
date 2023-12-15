import random

def rando_title(words):
    title = ''
    for i in range(random.randint(1,6)):
        title += ' '+random.choice(words)
    return title

filename = 'stream_titles.txt'
words = []

with open(filename, 'r') as sentences:
    for line in sentences:
        word_list = line.split(" ")
        stripped = [s.strip() for s in word_list]
        [words.append(word1) for word1 in stripped if not word1 in words]

wo = rando_title(words)
print(wo)

