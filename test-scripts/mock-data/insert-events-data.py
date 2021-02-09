import mysql.connector
import json


def readImportStatements(fileName = None):
    if fileName == None:
        fileName = 'Events.sql'

    inFile = open(fileName, 'r')

    lStmts = []
    for stmt in inFile:
        lStmts.append(stmt)

    inFile.close()

    return lStmts


print('hello')



stmts = readImportStatements()
for x in stmts:
    print(x)







